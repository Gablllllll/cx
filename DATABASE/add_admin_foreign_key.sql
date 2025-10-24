-- Add foreign key relationship between admin users and uploaded modules
-- This will track which specific admin uploaded each module

-- Step 1: Add uploaded_by_id column to learning_materials table
ALTER TABLE `learning_materials` 
ADD COLUMN `uploaded_by_id` INT(11) NOT NULL AFTER `description`;

-- Step 2: Update existing records to reference admin users
-- First, let's find an admin user to use as default
-- We'll use the first admin user found, or create a default admin if none exists

-- Update existing learning materials to reference admin user (assuming user_id 26 is admin)
UPDATE `learning_materials` 
SET `uploaded_by_id` = (
    SELECT `user_id` 
    FROM `users` 
    WHERE `role` = 'admin' 
    LIMIT 1
)
WHERE `uploaded_by_id` = 0 OR `uploaded_by_id` IS NULL;

-- Step 3: Add foreign key constraint
ALTER TABLE `learning_materials`
ADD CONSTRAINT `fk_learning_materials_uploaded_by` 
FOREIGN KEY (`uploaded_by_id`) 
REFERENCES `users`(`user_id`) 
ON DELETE CASCADE 
ON UPDATE CASCADE;

-- Step 4: Add index for better performance
ALTER TABLE `learning_materials`
ADD INDEX `idx_uploaded_by_id` (`uploaded_by_id`);

-- Step 5: Show the updated table structure
DESCRIBE `learning_materials`;
