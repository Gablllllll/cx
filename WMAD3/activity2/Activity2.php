<?php

include "../php/connect2.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Document</title>
</head>
<body>
        
    <table border="1">
        <h1>Houston Rockets (2022-23 Season)</h1>
        <tr>
            <th>Player Name</th>
            <th>Team Location</th>
            <th>Team Name</th>
            <th>Season</th>
        </tr>

        <?php
        $sql_query_get_table1 = mysqli_query($conn, 
            "SELECT nba_players.playerName, nba_teams.team_location, nba_teams.team_name, nba_stats.season 
            FROM nba_players 
            INNER JOIN nba_teams ON nba_players.teamID = nba_teams.team_uniq 
            INNER JOIN nba_stats ON nba_players.playerID = nba_stats.player_uniq 
            WHERE nba_teams.team_location = 'Houston' 
            AND nba_teams.team_name = 'Rockets' 
            AND nba_stats.season = '2022-23'");

        while ($row_table1 = mysqli_fetch_assoc($sql_query_get_table1)) {
            echo "<tr>
                    <td>{$row_table1['playerName']}</td>
                    <td>{$row_table1['team_location']}</td>
                    <td>{$row_table1['team_name']}</td>
                    <td>{$row_table1['season']}</td>
                </tr>";
        }
        ?>
    </table>

    <BR></BR>

    <table border="1">
        <h1>NBA Player Selection </h1>
        <tr>
            <th>Player Name</th>
            <th>Season</th>
            <th>Team Name</th>
            <th>Age</th>
            <th>Points</th>
            <th>Reb</th>
            <th>Ast</th>
            <th>Blk</th>
        </tr>

        <?php
        $sql_query_get_table2 = mysqli_query($conn, 
            "SELECT nba_players.playerName, nba_stats.season, nba_teams.team_name, nba_players.age, nba_stats.points, nba_stats.reb, nba_stats.ast, nba_stats.blk
            FROM nba_players 
            INNER JOIN nba_stats ON nba_players.playerID = nba_stats.player_uniq 
            INNER JOIN nba_teams ON nba_players.teamID = nba_teams.team_uniq 
            WHERE nba_players.playerName IN ('LeBron James', 'Stephen Curry', 'Bol Bol') 
            AND nba_stats.season = '2023-24'");



        while ($row_table2 = mysqli_fetch_assoc($sql_query_get_table2)) {
            echo "<tr>
                    <td>{$row_table2['playerName']}</td>
                    <td>{$row_table2['season']}</td>
                    <td>{$row_table2['team_name']}</td>
                    <td>{$row_table2['age']}</td>
                    <td>{$row_table2['points']}</td>
                    <td>{$row_table2['reb']}</td>
                    <td>{$row_table2['ast']}</td>
                    <td>{$row_table2['reb']}</td>
                </tr>";
            }
            ?>
        </table>
    
    </body>
</html>



