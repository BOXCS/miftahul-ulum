<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Parent Accounts
        $data = [
            [
                "name" => "Budi Santoso",
                "email" => "budi@example.com",
                "phone" => "081234567890",
                "relationship" => "ayah",
                "address" => "Jl. Merdeka No. 10, Jakarta",
            ],
            [
                "name" => "Siti Aminah",
                "email" => "siti@example.com",
                "phone" => "081298765432",
                "relationship" => "ibu",
                "address" => "Jl. Bahagia No. 5, Bandung",
            ],
        ];

        foreach ($data as $item) {
            // Create User entry
            $user = User::create([
                "name" => $item["name"],
                "email" => $item["email"],
                "password" => Hash::make("password123"),
            ]);

            // Create Parent entry linked to User
            $parent = ParentModel::create([
                "user_id" => $user->id,
                "name" => $item["name"],
                "email" => $item["email"],
                "phone" => $item["phone"],
                "relationship" => $item["relationship"],
                "address" => $item["address"],
            ]);

            // Create a dummy student for each parent so they have data
            Student::create([
                "parent_id" => $parent->id,
                "name" => "Ananda " . $item["name"],
                "nis" => "NIS" . rand(1000, 9999),
                "gender" =>
                    $item["relationship"] == "ayah" ? "Laki-laki" : "Perempuan",
                "tanggal_lahir" => "2015-05-15",
                "class" => "7A",
                "status" => "aktif",
                "tahun_angkatan" => "2024",
                "address" => $item["address"],
            ]);
        }
    }
}
