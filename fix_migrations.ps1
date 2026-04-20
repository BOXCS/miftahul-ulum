# Cleanup duplicate Laravel migrations
# This script removes conflicting and old migration files

$projectRoot = "E:\kuliah\Teknik Informatika 23\Project Laravel\miftahul_ulumV2"
$migrationsPath = "$projectRoot\database\migrations"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Migration Cleanup Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Files to DELETE (duplicates and wrong-numbered versions)
$filesToDelete = @(
    "0001_01_01_000001_create_cache_table.php",      # DUPLICATE - cache should be 000008
    "0001_01_01_000002_create_jobs_table.php",       # DUPLICATE - jobs should be 000009
    "0001_01_01_000001_create_students_table.php",   # WRONG NUMBER - conflicts with cache duplicate
    "0001_01_01_000002_create_parents_table.php",    # WRONG NUMBER - conflicts with jobs duplicate
    "2025_01_01_000010_create_santri_table.php",     # OLD - duplicate of students
    "2025_01_01_000011_create_wali_santri_table.php",# OLD - duplicate of parents
    "2025_01_01_000012_create_absensi_table.php",    # OLD - duplicate of attendance
    "2025_01_01_000013_create_pesan_table.php",      # OLD - duplicate of chat_messages
    "2025_01_01_000014_create_pengumuman_table.php", # OLD - duplicate of announcements
    "2025_01_01_000015_create_faq_table.php",        # OLD - duplicate of faqs
    "2025_01_01_000016_create_izin_table.php"        # OLD - duplicate of permissions
)

Write-Host "Removing $($filesToDelete.Count) duplicate/old migration files...`n"

$deletedCount = 0
foreach ($file in $filesToDelete) {
    $fullPath = Join-Path $migrationsPath $file
    try {
        if (Test-Path $fullPath) {
            Remove-Item $fullPath -Force -ErrorAction Stop
            Write-Host "✓ Deleted: $file" -ForegroundColor Green
            $deletedCount++
        } else {
            Write-Host "⊘ Not found: $file" -ForegroundColor Yellow
        }
    } catch {
        Write-Host "✗ Error deleting $file : $_" -ForegroundColor Red
    }
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "Cleanup Results:" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Files deleted: $deletedCount / $($filesToDelete.Count)" -ForegroundColor Green
Write-Host ""

# Verify correct migration files exist
Write-Host "Verifying correct migrations exist..." -ForegroundColor Cyan
$correctFiles = @(
    "0001_01_01_000000_create_users_table.php",
    "0001_01_01_000000_create_sessions_table.php",
    "0001_01_01_000001_create_students_table.php",
    "0001_01_01_000002_create_parents_table.php",
    "0001_01_01_000003_create_attendance_table.php",
    "0001_01_01_000004_create_announcements_table.php",
    "0001_01_01_000005_create_chat_messages_table.php",
    "0001_01_01_000006_create_faqs_table.php",
    "0001_01_01_000007_create_permissions_table.php",
    "0001_01_01_000008_create_cache_table.php",
    "0001_01_01_000009_create_jobs_table.php"
)

$missingCount = 0
foreach ($file in $correctFiles) {
    $fullPath = Join-Path $migrationsPath $file
    if (Test-Path $fullPath) {
        Write-Host "✓ Found: $file" -ForegroundColor Green
    } else {
        Write-Host "✗ MISSING: $file" -ForegroundColor Red
        $missingCount++
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan

if ($deletedCount -eq $filesToDelete.Count -and $missingCount -eq 0) {
    Write-Host "✅ SUCCESS! All migrations are clean!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next step: Run this command in your terminal:" -ForegroundColor Yellow
    Write-Host "  php artisan migrate:fresh --seed --seeder=DemoDataSeeder" -ForegroundColor Cyan
    Write-Host ""
} else {
    if ($missingCount -gt 0) {
        Write-Host "⚠️  WARNING: $missingCount correct files are missing!" -ForegroundColor Yellow
    }
    Write-Host "Check the migration files manually before proceeding." -ForegroundColor Yellow
}

Write-Host "========================================`n" -ForegroundColor Cyan
Read-Host "Press Enter to exit"
