<?php
// Cleanup script to remove duplicate migration files

$migrationsPath = __DIR__ . '/database/migrations';

$filesToDelete = [
    '0001_01_01_000001_create_cache_table.php',
    '0001_01_01_000002_create_jobs_table.php',
    '0001_01_01_000001_create_students_table.php',
    '0001_01_01_000002_create_parents_table.php',
    '2025_01_01_000010_create_santri_table.php',
    '2025_01_01_000011_create_wali_santri_table.php',
    '2025_01_01_000012_create_absensi_table.php',
    '2025_01_01_000013_create_pesan_table.php',
    '2025_01_01_000014_create_pengumuman_table.php',
    '2025_01_01_000015_create_faq_table.php',
    '2025_01_01_000016_create_izin_table.php',
];

echo "\n========================================\n";
echo "Migration Cleanup Script (PHP)\n";
echo "========================================\n\n";
echo "Removing " . count($filesToDelete) . " duplicate/old migration files...\n\n";

$deletedCount = 0;
foreach ($filesToDelete as $index => $file) {
    $fullPath = $migrationsPath . '/' . $file;
    if (file_exists($fullPath)) {
        if (unlink($fullPath)) {
            echo "[" . ($index + 1) . "/" . count($filesToDelete) . "] ✓ Deleted: $file\n";
            $deletedCount++;
        } else {
            echo "[" . ($index + 1) . "/" . count($filesToDelete) . "] ✗ Failed to delete: $file\n";
        }
    } else {
        echo "[" . ($index + 1) . "/" . count($filesToDelete) . "] ⊘ Not found: $file\n";
    }
}

echo "\n========================================\n";
echo "Results:\n";
echo "========================================\n";
echo "Files deleted: $deletedCount / " . count($filesToDelete) . "\n\n";

// Verify correct files exist
$correctFiles = [
    '0001_01_01_000000_create_users_table.php',
    '0001_01_01_000000_create_sessions_table.php',
    '0001_01_01_000001_create_students_table.php',
    '0001_01_01_000002_create_parents_table.php',
    '0001_01_01_000003_create_attendance_table.php',
    '0001_01_01_000004_create_announcements_table.php',
    '0001_01_01_000005_create_chat_messages_table.php',
    '0001_01_01_000006_create_faqs_table.php',
    '0001_01_01_000007_create_permissions_table.php',
    '0001_01_01_000008_create_cache_table.php',
    '0001_01_01_000009_create_jobs_table.php',
];

echo "Verifying correct migrations:\n";
$missingCount = 0;
foreach ($correctFiles as $file) {
    $fullPath = $migrationsPath . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✓ Found: $file\n";
    } else {
        echo "✗ MISSING: $file\n";
        $missingCount++;
    }
}

echo "\n========================================\n";
if ($deletedCount === count($filesToDelete) && $missingCount === 0) {
    echo "✅ SUCCESS! All migrations are clean!\n\n";
    echo "Next step: Run in your terminal:\n";
    echo "  php artisan migrate:fresh --seed --seeder=DemoDataSeeder\n";
} else {
    echo "⚠️  Check results above\n";
}
echo "========================================\n\n";
