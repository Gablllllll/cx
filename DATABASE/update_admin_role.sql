-- Update admin user names to be more professional
-- This will fix the "jonel jonel" display issue

-- Update the admin user with user_id 19 (jonel) to have a proper name
UPDATE `users` 
SET `first_name` = 'SPLD Admin' 
WHERE `user_id` = 19 AND `username` = 'jonel';

-- Update the admin user with user_id 26 (tutor) to have a proper name  
UPDATE `users` 
SET `first_name` = 'SPLD Admin' 
WHERE `user_id` = 26 AND `username` = 'tutor';

-- Update the admin user with user_id 27 (junneltutor) to have a proper name
UPDATE `users` 
SET `first_name` = 'Junnel' 
WHERE `user_id` = 27 AND `username` = 'junneltutor';

UPDATE `users` 
SET `first_name` = 'SPLD Admin', `role` = 'admin' 
WHERE `user_id` = 39 AND `username` = 'joseph';
