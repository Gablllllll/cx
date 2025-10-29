<?php
include "myconnector.php";
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// --- MOVE THIS BLOCK UP HERE ---
if (isset($_GET['get_users'])) {
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT user_id, COALESCE(first_name, '') AS first_name, COALESCE(last_name, '') AS last_name, role FROM users WHERE user_id != $user_id");
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $first = isset($row['first_name']) ? $row['first_name'] : '';
        $last = isset($row['last_name']) ? $row['last_name'] : '';
        $fullName = trim($first . ' ' . $last);
        $users[] = [
            'user_id' => $row['user_id'],
            'name' => ($fullName !== '' ? $fullName : ($row['role'] . ' #' . $row['user_id'])),
            'role' => $row['role']
        ];
    }
    echo json_encode($users);
    exit;
}
// --- END MOVE ---

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Join users table twice to get names for tutor and student
    $result = mysqli_query($conn, "
        SELECT s.*, 
               t.first_name AS tutor_first, t.last_name AS tutor_last,
               stu.first_name AS student_first, stu.last_name AS student_last
        FROM schedules s
        LEFT JOIN users t ON s.tutor_id = t.user_id
        LEFT JOIN users stu ON s.student_id = stu.user_id
        WHERE s.created_by = $user_id OR s.student_id = $user_id OR s.tutor_id = $user_id
    ");
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = [
            'id' => $row['schedule_id'],
            'title' => ($row['is_booked'] ? 'Booked: ' : 'Available: ') . $row['day'],
            'start' => $row['session_date'] . 'T' . $row['available_from'],
            'end' => $row['session_date'] . 'T' . $row['available_to'],
            'color' => $row['is_booked'] ? '#dc3545' : '#0d6efd',
            'extendedProps' => [
                'tutor_id' => $row['tutor_id'],
                'student_id' => $row['student_id'],
                'location_mode' => $row['location_mode'],
                'status' => $row['status'],
                'is_booked' => $row['is_booked'],
                'created_by' => $row['created_by'],
                'tutor_name' => trim($row['tutor_first'] . ' ' . $row['tutor_last']),
                'student_name' => trim($row['student_first'] . ' ' . $row['student_last'])
            ]
        ];
    }
    echo json_encode($events);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    // CREATE slot (allow both tutor and student)
    if ($action === 'create') {
        $other_user_id = intval($data['other_user_id']);
        $other_user_role = $data['other_user_role'];
        $day = mysqli_real_escape_string($conn, $data['day']);
        $session_date = mysqli_real_escape_string($conn, $data['session_date']);
        $available_from = mysqli_real_escape_string($conn, $data['available_from']);
        $available_to = mysqli_real_escape_string($conn, $data['available_to']);
        $location_mode = mysqli_real_escape_string($conn, $data['location_mode']);

        // Validate IDs
        if (!$other_user_id || !$user_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid user selection.']);
            exit;
        }

        // Set tutor_id or student_id based on the selected user's role
        if ($other_user_role === 'tutor') {
            $tutor_id = $other_user_id;
            $student_id = $user_id;
        } else {
            $tutor_id = $user_id;
            $student_id = $other_user_id;
        }

        // Double-check both IDs exist in users table
        $check = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_id IN ($tutor_id, $student_id)");
        $row = mysqli_fetch_assoc($check);
        if ($row['cnt'] < 2) {
            echo json_encode(['success' => false, 'message' => 'User does not exist.']);
            exit;
        }

        $sql = "INSERT INTO schedules (tutor_id, student_id, day, available_from, available_to, session_date, location_mode, created_by) 
                VALUES ($tutor_id, $student_id, '$day', '$available_from', '$available_to', '$session_date', '$location_mode', $user_id)";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true, 'id' => mysqli_insert_id($conn)]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        }
        exit;
    }

    // TUTOR: UPDATE slot
    if ($action === 'update' && $user_role === 'tutor') {
        $id = intval($data['id']);
        $day = mysqli_real_escape_string($conn, $data['day']);
        $session_date = mysqli_real_escape_string($conn, $data['session_date']);
        $available_from = mysqli_real_escape_string($conn, $data['available_from']);
        $available_to = mysqli_real_escape_string($conn, $data['available_to']);
        $location_mode = mysqli_real_escape_string($conn, $data['location_mode']);
        $sql = "UPDATE schedules SET day='$day', session_date='$session_date', available_from='$available_from', available_to='$available_to', location_mode='$location_mode' WHERE schedule_id=$id AND tutor_id=$user_id";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        }
        exit;
    }

    // TUTOR: DELETE slot
    if ($action === 'delete' && $user_role === 'tutor') {
        $id = intval($data['id']);
        $sql = "DELETE FROM schedules WHERE schedule_id=$id AND tutor_id=$user_id";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        }
        exit;
    }

    // STUDENT: BOOK slot
    if ($action === 'book' && $user_role === 'student') {
        $id = intval($data['id']);
        $sql = "UPDATE schedules SET student_id=$user_id, is_booked=1 WHERE schedule_id=$id AND is_booked=0";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        }
        exit;
    }

    // STUDENT: CANCEL booking
    if ($action === 'cancel' && $user_role === 'student') {
        $id = intval($data['id']);
        $sql = "UPDATE schedules SET student_id=NULL, is_booked=0 WHERE schedule_id=$id AND student_id=$user_id";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        }
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);