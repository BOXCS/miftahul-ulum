@echo off
setlocal enabledelayedexpansion

REM Cleanup all duplicate and old migration files
REM This removes 11 conflicting files and leaves only the correct 11

cd /d "E:\kuliah\Teknik Informatika 23\Project Laravel\miftahul_ulumV2\database\migrations"

echo.
echo ========================================
echo Migration Cleanup Script
echo ========================================
echo.
echo Removing 11 duplicate/old migration files...
echo.

set deletedCount=0

REM Delete DUPLICATE and WRONG-NUMBERED migrations
del /F /Q "0001_01_01_000001_create_cache_table.php" 2>nul && (
  echo [1/11] Deleted: 0001_01_01_000001_create_cache_table.php
  set /a deletedCount+=1
)

del /F /Q "0001_01_01_000002_create_jobs_table.php" 2>nul && (
  echo [2/11] Deleted: 0001_01_01_000002_create_jobs_table.php
  set /a deletedCount+=1
)

del /F /Q "0001_01_01_000001_create_students_table.php" 2>nul && (
  echo [3/11] Deleted: 0001_01_01_000001_create_students_table.php (CONFLICT)
  set /a deletedCount+=1
)

del /F /Q "0001_01_01_000002_create_parents_table.php" 2>nul && (
  echo [4/11] Deleted: 0001_01_01_000002_create_parents_table.php (CONFLICT)
  set /a deletedCount+=1
)

REM Delete OLD 2025_ migrations
del /F /Q "2025_01_01_000010_create_santri_table.php" 2>nul && (
  echo [5/11] Deleted: 2025_01_01_000010_create_santri_table.php
  set /a deletedCount+=1
)

del /F /Q "2025_01_01_000011_create_wali_santri_table.php" 2>nul && (
  echo [6/11] Deleted: 2025_01_01_000011_create_wali_santri_table.php
  set /a deletedCount+=1
)

del /F /Q "2025_01_01_000012_create_absensi_table.php" 2>nul && (
  echo [7/11] Deleted: 2025_01_01_000012_create_absensi_table.php
  set /a deletedCount+=1
)

del /F /Q "2025_01_01_000013_create_pesan_table.php" 2>nul && (
  echo [8/11] Deleted: 2025_01_01_000013_create_pesan_table.php
  set /a deletedCount+=1
)

del /F /Q "2025_01_01_000014_create_pengumuman_table.php" 2>nul && (
  echo [9/11] Deleted: 2025_01_01_000014_create_pengumuman_table.php
  set /a deletedCount+=1
)

del /F /Q "2025_01_01_000015_create_faq_table.php" 2>nul && (
  echo [10/11] Deleted: 2025_01_01_000015_create_faq_table.php
  set /a deletedCount+=1
)

del /F /Q "2025_01_01_000016_create_izin_table.php" 2>nul && (
  echo [11/11] Deleted: 2025_01_01_000016_create_izin_table.php
  set /a deletedCount+=1
)

echo.
echo ========================================
echo Cleanup Results:
echo ========================================
echo Files deleted: !deletedCount! / 11
echo.

echo Verifying remaining migrations:
echo.
if exist "0001_01_01_000000_create_users_table.php" (
  echo [OK] 0001_01_01_000000_create_users_table.php
) else (
  echo [MISSING] 0001_01_01_000000_create_users_table.php
)

if exist "0001_01_01_000000_create_sessions_table.php" (
  echo [OK] 0001_01_01_000000_create_sessions_table.php
) else (
  echo [MISSING] 0001_01_01_000000_create_sessions_table.php
)

if exist "0001_01_01_000001_create_students_table.php" (
  echo [OK] 0001_01_01_000001_create_students_table.php (AFTER DELETION)
) else (
  echo [MISSING] 0001_01_01_000001_create_students_table.php
)

if exist "0001_01_01_000002_create_parents_table.php" (
  echo [OK] 0001_01_01_000002_create_parents_table.php (AFTER DELETION)
) else (
  echo [MISSING] 0001_01_01_000002_create_parents_table.php
)

if exist "0001_01_01_000003_create_attendance_table.php" (
  echo [OK] 0001_01_01_000003_create_attendance_table.php
) else (
  echo [MISSING] 0001_01_01_000003_create_attendance_table.php
)

if exist "0001_01_01_000004_create_announcements_table.php" (
  echo [OK] 0001_01_01_000004_create_announcements_table.php
) else (
  echo [MISSING] 0001_01_01_000004_create_announcements_table.php
)

if exist "0001_01_01_000005_create_chat_messages_table.php" (
  echo [OK] 0001_01_01_000005_create_chat_messages_table.php
) else (
  echo [MISSING] 0001_01_01_000005_create_chat_messages_table.php
)

if exist "0001_01_01_000006_create_faqs_table.php" (
  echo [OK] 0001_01_01_000006_create_faqs_table.php
) else (
  echo [MISSING] 0001_01_01_000006_create_faqs_table.php
)

if exist "0001_01_01_000007_create_permissions_table.php" (
  echo [OK] 0001_01_01_000007_create_permissions_table.php
) else (
  echo [MISSING] 0001_01_01_000007_create_permissions_table.php
)

if exist "0001_01_01_000008_create_cache_table.php" (
  echo [OK] 0001_01_01_000008_create_cache_table.php
) else (
  echo [MISSING] 0001_01_01_000008_create_cache_table.php
)

if exist "0001_01_01_000009_create_jobs_table.php" (
  echo [OK] 0001_01_01_000009_create_jobs_table.php
) else (
  echo [MISSING] 0001_01_01_000009_create_jobs_table.php
)

echo.
echo ========================================

if !deletedCount! equ 11 (
  echo.
  echo SUCCESS! All migrations are clean!
  echo.
  echo Next step: Run this command in your terminal:
  echo   php artisan migrate:fresh --seed --seeder=DemoDataSeeder
  echo.
) else (
  echo.
  echo WARNING: Some files may not have been deleted.
  echo Please verify the migration folder manually.
  echo.
)

echo ========================================
echo.
pause
