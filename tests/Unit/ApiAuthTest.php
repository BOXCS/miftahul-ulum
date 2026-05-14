<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_berhasil_dengan_kredensial_benar()
    {
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    /** @test */
    public function login_gagal_dengan_kredensial_salah()
    {
        User::factory()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@example.com',
            'password' => 'salah_password',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['success' => false]);
    }

    /** @test */
    public function login_gagal_tanpa_email()
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
    }
}