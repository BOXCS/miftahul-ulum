@echo off
REM This script removes duplicate/old migration files

cd /d "E:\kuliah\Teknik Informatika 23\Project Laravel\miftahul_ulumV2\database\migrations"

echo Removing old and duplicate migration files...
echo.

REM Delete old 2025_ migrations
del /F /Q "2025_01_01_000016_create_izin_table.php" 2>nul && echo Deleted: 2025_01_01_000016_create_izin_table.php
del /F /Q "2025_01_01_000015_create_faq_table.php" 2>nul && echo Deleted: 2025_01_01_000015_create_faq_table.php
del /F /Q "2025_01_01_000014_create_pengumuman_table.php" 2>nul && echo Deleted: 2025_01_01_000014_create_pengumuman_table.php
del /F /Q "2025_01_01_000013_create_pesan_table.php" 2>nul && echo Deleted: 2025_01_01_000013_create_pesan_table.php
del /F /Q "2025_01_01_000012_create_absensi_table.php" 2>nul && echo Deleted: 2025_01_01_000012_create_absensi_table.php
del /F /Q "2025_01_01_000011_create_wali_santri_table.php" 2>nul && echo Deleted: 2025_01_01_000011_create_wali_santri_table.php
del /F /Q "2025_01_01_000010_create_santri_table.php" 2>nul && echo Deleted: 2025_01_01_000010_create_santri_table.php

REM Delete DUPLICATE migrations with conflicting timestamps
del /F /Q "0001_01_01_000001_create_cache_table.php" 2>nul && echo Deleted: 0001_01_01_000001_create_cache_table.php (DUPLICATE)
del /F /Q "0001_01_01_000002_create_jobs_table.php" 2>nul && echo Deleted: 0001_01_01_000002_create_jobs_table.php (DUPLICATE)

echo.
echo ============================================
echo Cleanup complete!
echo.
echo You can now run:
echo   php artisan migrate:fresh --seed --seeder=DemoDataSeeder
echo ============================================
pause
