<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiChatTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function chat_history_return_array()
    {
        // Buat parent dummy
        $parent = \App\Models\ParentModel::factory()->create();

        $response = $this->getJson("/api/chat/{$parent->id}/history");

        $response->assertStatus(200);
        $this->assertIsArray($response->json());
    }

    /** @test */
    public function send_message_tanpa_pesan_return_error()
    {
        $parent = \App\Models\ParentModel::factory()->create();

        $response = $this->postJson("/api/chat/{$parent->id}/send-api", []);

        // Harus return error karena field 'pesan' kosong
        $response->assertStatus(422);
    }
}