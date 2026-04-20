<?php
// Remove old Parent.php file that conflicts with reserved keyword

$oldFile = __DIR__ . '/app/Models/Parent.php';

if (file_exists($oldFile)) {
    if (unlink($oldFile)) {
        echo "✅ Successfully deleted: app/Models/Parent.php\n";
    } else {
        echo "❌ Failed to delete: app/Models/Parent.php\n";
    }
} else {
    echo "⊘ File not found: app/Models/Parent.php\n";
}

echo "\nYou can now run:\n";
echo "  php artisan migrate:fresh --seed --seeder=DemoDataSeeder\n";
