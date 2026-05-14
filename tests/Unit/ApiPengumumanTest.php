<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiPengumumanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pengumuman_endpoint_return_status_200()
    {
        $response = $this->getJson('/api/pengumuman');

        $response->assertStatus(200);
    }

    /** @test */
    public function pengumuman_return_format_array()
    {
        $response = $this->getJson('/api/pengumuman');

        $response->assertStatus(200)
                 ->assertJsonStructure([]);

        $this->assertIsArray($response->json());
    }
}