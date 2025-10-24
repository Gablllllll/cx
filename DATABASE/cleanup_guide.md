# Database Cleanup Guide

## Step 1: Backup Your Current Database
Before making any changes, create a backup of your current database.

## Step 2: Run the Cleanup Script
Execute the cleanup_database.sql script to remove unnecessary components:

```sql
-- Run this in phpMyAdmin or MySQL command line
SOURCE DATABASE/cleanup_database.sql;
```

## Step 3: Verify the Cleanup
After running the cleanup script, your database should only have:

### Users Table (Essential Columns Only):
- user_id (Primary Key)
- username
- role (student/admin)
- first_name
- last_name
- email
- password_hash
- contact_number
- address
- date_of_birth
- created_at

### Learning Materials Table (Essential Columns Only):
- material_id (Primary Key)
- title
- description
- file_url
- upload_date
- is_approved

## Step 4: Test Your Application
After cleanup, test your application to ensure:
- User login still works
- Module viewing still works
- Profile management still works
- All core functionality is preserved

## What Was Removed:
- ❌ feedback table (unused)
- ❌ sessions table (unused)
- ❌ schedules table (unused)
- ❌ tutor_applications table (unused)
- ❌ secret_key column from users
- ❌ profile_pic column from users
- ❌ status column from users
- ❌ uploaded_by column from learning_materials
- ❌ uploaded_by_id column from learning_materials
- ❌ approved_by column from learning_materials
- ❌ approved_at column from learning_materials

## What Was Kept:
- ✅ Core user management
- ✅ Learning materials management
- ✅ Authentication system
- ✅ Profile management
- ✅ Module viewing system
