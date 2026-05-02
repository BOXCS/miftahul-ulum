<?php
$file = 'database/seeders/DemoDataSeeder.php';
$content = file_get_contents($file);

$find = <<<PHP
                "status" => "aktif",
                "tahun_angkatan" => "2022",
            ],
PHP;

$replace = <<<PHP
                "status" => "aktif",
                "tahun_angkatan" => "2022",
                "fingerprint_template" => encrypt("BASE64_TEMPLATE_ahmadfauzi"),
                "fingerprint_quality" => 95,
                "scanned_at" => now(),
            ],
PHP;

// Only replace the first occurrence
$content = preg_replace('/' . preg_quote($find, '/') . '/', $replace, $content, 1);

// Add for another student
$find2 = <<<PHP
                "status" => "aktif",
                "tahun_angkatan" => "2023",
            ],
PHP;

$replace2 = <<<PHP
                "status" => "aktif",
                "tahun_angkatan" => "2023",
                "fingerprint_template" => encrypt("BASE64_TEMPLATE_muhammadhasan"),
                "fingerprint_quality" => 88,
                "scanned_at" => now(),
            ],
PHP;

$content = preg_replace('/' . preg_quote($find2, '/') . '/', $replace2, $content, 1);

file_put_contents($file, $content);
echo "Seeder updated.\n";
