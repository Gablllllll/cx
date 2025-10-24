-- Create feedback table with foreign key to users
-- This will allow students to provide feedback on modules

CREATE TABLE IF NOT EXISTS `feedback` (
    `feedback_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `material_id` INT NOT NULL,
    `rating` INT NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`material_id`) REFERENCES `learning_materials`(`material_id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_user_material_feedback` (`user_id`, `material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add index for better performance
ALTER TABLE `feedback` ADD INDEX `idx_material_id` (`material_id`);
ALTER TABLE `feedback` ADD INDEX `idx_user_id` (`user_id`);

-- Show the created table structure
DESCRIBE `feedback`;
