<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\ParentModel;
use App\Models\Attendance;
use App\Models\Announcement;
use App\Models\ChatMessage;
use App\Models\Faq;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Parents First
        $parents = [
            [
                "name" => "Bapak Ahmad Basri",
                "relationship" => "ayah",
                "phone" => "081234567890",
                "email" => "ahmad.basri@email.com",
                "address" => "Jl. Melati No. 12, Surabaya",
            ],
            [
                "name" => "Ibu Siti Rahmawati",
                "relationship" => "ibu",
                "phone" => "082345678901",
                "email" => "siti.rahmawati@email.com",
                "address" => "Jl. Mawar No. 5, Malang",
            ],
            [
                "name" => "Bapak Hendra Kusuma",
                "relationship" => "ayah",
                "phone" => "083456789012",
                "email" => "hendra.kusuma@email.com",
                "address" => "Jl. Kenanga No. 8, Sidoarjo",
            ],
            [
                "name" => "Bapak Rudi Santoso",
                "relationship" => "ayah",
                "phone" => "085678901234",
                "email" => "rudi.santoso@email.com",
                "address" => "Jl. Anggrek No. 17, Mojokerto",
            ],
            [
                "name" => "Paman Joko Widodo",
                "relationship" => "wali",
                "phone" => "086789012345",
                "email" => "joko.widodo@email.com",
                "address" => "Jl. Flamboyan No. 22, Surabaya",
            ],
            [
                "name" => "Ibu Nur Hasanah",
                "relationship" => "ibu",
                "phone" => "087890123456",
                "email" => "nur.hasanah@email.com",
                "address" => "Jl. Teratai No. 9, Lamongan",
            ],
            [
                "name" => "Bapak Agus Priyanto",
                "relationship" => "ayah",
                "phone" => "088901234567",
                "email" => "agus.priyanto@email.com",
                "address" => "Jl. Tulip No. 6, Pasuruan",
            ],
        ];

        $parentModels = [];
        foreach ($parents as $parent) {
            $parentModels[] = ParentModel::create($parent);
        }

        // Seed Students and link to Parents
        $students = [
            [
                "parent_id" => $parentModels[0]->id,
                "name" => "Ahmad Fauzi",
                "nis" => "12345001",
                "email" => "ahmad.fauzi@email.com",
                "phone" => "081234567890",
                "class" => "9A",
                "address" => "Jl. Mawar No. 12, Bandung",
                "status" => "aktif",
                "tahun_angkatan" => "2022",
            ],
            [
                "parent_id" => $parentModels[1]->id,
                "name" => "Siti Rahayu",
                "nis" => "12345002",
                "email" => "siti.rahayu@email.com",
                "phone" => "082345678901",
                "class" => "9B",
                "address" => "Jl. Melati No. 5, Sumedang",
                "status" => "aktif",
                "tahun_angkatan" => "2022",
            ],
            [
                "parent_id" => $parentModels[2]->id,
                "name" => "Muhammad Hasan",
                "nis" => "12345003",
                "email" => "muhammad.hasan@email.com",
                "phone" => "083456789012",
                "class" => "8A",
                "address" => "Jl. Anggrek No. 8, Garut",
                "status" => "aktif",
                "tahun_angkatan" => "2023",
            ],
            [
                "parent_id" => $parentModels[3]->id,
                "name" => "Zahra Nur Fadilah",
                "nis" => "12345004",
                "email" => "zahra.fadilah@email.com",
                "phone" => "084567890123",
                "class" => "8B",
                "address" => "Jl. Kenanga No. 3, Tasikmalaya",
                "status" => "aktif",
                "tahun_angkatan" => "2023",
            ],
            [
                "parent_id" => $parentModels[4]->id,
                "name" => "Rizki Ramadhan",
                "nis" => "12345005",
                "email" => "rizki.ramadhan@email.com",
                "phone" => "085678901234",
                "class" => "7A",
                "address" => "Jl. Dahlia No. 17, Ciamis",
                "status" => "aktif",
                "tahun_angkatan" => "2024",
            ],
            [
                "parent_id" => $parentModels[5]->id,
                "name" => "Nurul Hidayah",
                "nis" => "12345006",
                "email" => "nurul.hidayah@email.com",
                "phone" => "086789012345",
                "class" => "7B",
                "address" => "Jl. Flamboyan No. 9, Cianjur",
                "status" => "aktif",
                "tahun_angkatan" => "2024",
            ],
            [
                "parent_id" => $parentModels[0]->id,
                "name" => "Abdullah Mubarok",
                "nis" => "12345007",
                "email" => "abdullah.mubarok@email.com",
                "phone" => "087890123456",
                "class" => "9A",
                "address" => "Jl. Cempaka No. 21, Kuningan",
                "status" => "aktif",
                "tahun_angkatan" => "2022",
            ],
            [
                "parent_id" => $parentModels[1]->id,
                "name" => "Fatimah Az-Zahra",
                "nis" => "12345008",
                "email" => "fatimah.azzahra@email.com",
                "phone" => "088901234567",
                "class" => "9B",
                "address" => "Jl. Seroja No. 6, Majalengka",
                "status" => "alumni",
                "tahun_angkatan" => "2021",
            ],
            [
                "parent_id" => $parentModels[6]->id,
                "name" => "Dimas Pratama",
                "nis" => "12345009",
                "email" => "dimas.pratama@email.com",
                "phone" => "089012345678",
                "class" => "8A",
                "address" => "Jl. Tulip No. 14, Purwakarta",
                "status" => "aktif",
                "tahun_angkatan" => "2023",
            ],
            [
                "parent_id" => $parentModels[4]->id,
                "name" => "Salwa Aulia",
                "nis" => "12345010",
                "email" => "salwa.aulia@email.com",
                "phone" => "081123456789",
                "class" => "7A",
                "address" => "Jl. Bougenville No. 2, Subang",
                "status" => "aktif",
                "tahun_angkatan" => "2024",
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }

        // Seed Attendance (Prayer-based)
        $today = Carbon::today();
        $prayers = ["Subuh", "Dzuhur", "Ashar", "Maghrib", "Isya"];
        $statuses = ["hadir", "terlambat", "alpha"];

        foreach (
            Student::where("status", "aktif")->limit(5)->get()
            as $student
        ) {
            foreach ($prayers as $prayer) {
                Attendance::create([
                    "student_id" => $student->id,
                    "tanggal" => $today->toDateString(),
                    "waktu_shalat" => $prayer,
                    "status" => $statuses[array_rand($statuses)],
                    "jam_masuk" => $today
                        ->copy()
                        ->setHour(rand(4, 20))
                        ->setMinute(rand(0, 59)),
                    "keterangan" => null,
                ]);
            }
        }

        // Seed Announcements
        $announcements = [
            [
                "judul" => "Libur Hari Raya Idul Fitri",
                "konten" =>
                    "Diberitahukan kepada seluruh santri dan wali santri bahwa pesantren akan libur selama 7 hari dalam rangka Hari Raya Idul Fitri 1446 H.",
                "kategori" => "kegiatan",
                "is_published" => true,
                "published_at" => "2025-03-20 08:00:00",
            ],
            [
                "judul" => "Jadwal Ujian Semester Genap 2024/2025",
                "konten" =>
                    "Ujian semester genap akan dilaksanakan mulai tanggal 10 April 2025 hingga 20 April 2025.",
                "kategori" => "akademik",
                "is_published" => true,
                "published_at" => "2025-03-18 09:00:00",
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }

        // Seed FAQs
        $faqs = [
            [
                "pertanyaan" => "Bagaimana cara mendaftarkan santri baru?",
                "jawaban" =>
                    "Pendaftaran santri baru dapat dilakukan dengan mengisi formulir pendaftaran yang tersedia di kantor administrasi atau melalui website resmi pesantren.",
                "kategori" => "Pendaftaran",
                "urutan" => 1,
                "is_active" => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        // Seed Permissions
        $permissions = [
            [
                "student_id" => 1,
                "jenis" => "pulang",
                "tanggal_mulai" => "2025-07-10",
                "tanggal_selesai" => "2025-07-12",
                "keterangan" => "Menghadiri acara keluarga di kampung halaman.",
                "status" => "pending",
                "approved_by" => null,
                "approved_at" => null,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Seed Chat Messages
        $messages = [
            [
                "parent_id" => 1,
                "pesan" =>
                    "Assalamualaikum, bagaimana perkembangan anak saya, Ahmad Fauzi?",
                "is_from_admin" => false,
                "is_read" => true,
                "read_at" => "2025-01-15 10:20:00",
            ],
            [
                "parent_id" => 1,
                "pesan" =>
                    'Wa\'alaikumussalam, Bapak. Alhamdulillah, perkembangan Ahmad sangat baik.',
                "is_from_admin" => true,
                "is_read" => true,
                "read_at" => "2025-01-15 10:25:00",
            ],
        ];

        foreach ($messages as $message) {
            ChatMessage::create($message);
        }

        $this->command->info(
            "Demo data seeded successfully with new structure!",
        );
    }
}
