<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            ]
        ];

        foreach ($parents as $parentData) {
            // Create User entry with password
            $user = User::create([
                "name" => $parentData["name"],
                "email" => $parentData["email"],
                "password" => Hash::make("password123"), // Password added here
            ]);

            // Create Parent entry linked to User
            $parent = ParentModel::create([
                "user_id" => $user->id,
                "name" => $parentData["name"],
                "email" => $parentData["email"],
                "phone" => $parentData["phone"],
                "relationship" => $parentData["relationship"],
                "address" => $parentData["address"],
                "password" => Hash::make("password123"), // Password added here for Parent model
                "role" => "ortu", // Set the role explicitly
            ]);

            // Create a dummy student for each parent
            Student::create([
                "parent_id" => $parent->id,
                "name" => "Ananda " . $parentData["name"],
                "nis" => "NIS" . rand(1000, 9999),
                "gender" => $parentData["relationship"] == "ayah" ? "Laki-laki" : "Perempuan",
                "tanggal_lahir" => "2015-05-15", // Date of birth
                "class" => "7A",
                "status" => "aktif",
                "tahun_angkatan" => "2024",
                "address" => $parentData["address"],
            ]);
        }
    }
}