<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\ParentModel;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

test('halaman chat dapat diakses oleh user login', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/chat');
    $response->assertStatus(200);
});

test('halaman chat redirect ke login jika belum login', function () {
    $response = $this->get('/chat');
    $response->assertRedirect('/login');
});

test('pesan berhasil dikirim dari web', function () {
    Event::fake();
    $user   = User::factory()->create();
    $parent = ParentModel::create([
        'name'     => 'Test Parent',
        'email'    => 'parent@test.com',
        'password' => bcrypt('password'),
        'phone'    => '08123456789',
        'role'     => 'ortu',
    ]);

    $response = $this->actingAs($user)->post("/chat/{$parent->id}", [
        'pesan' => 'Halo, ini pesan test',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('chat_messages', [
        'parent_id'     => $parent->id,
        'pesan'         => 'Halo, ini pesan test',
        'is_from_admin' => true,
    ]);
});

test('pesan gagal dikirim tanpa konten', function () {
    $user   = User::factory()->create();
    $parent = ParentModel::create([
        'name'     => 'Test Parent',
        'email'    => 'parent@test.com',
        'password' => bcrypt('password'),
        'phone'    => '08123456789',
        'role'     => 'ortu',
    ]);

    $response = $this->actingAs($user)->post("/chat/{$parent->id}", [
        'pesan' => '',
    ]);

    $response->assertSessionHasErrors('pesan');
});

test('chat history API return array', function () {
    $parent = ParentModel::create([
        'name'     => 'Test Parent',
        'email'    => 'parent@test.com',
        'password' => bcrypt('password'),
        'phone'    => '08123456789',
        'role'     => 'ortu',
    ]);

    $response = $this->getJson("/api/chat/{$parent->id}/history");
    $response->assertStatus(200);
    expect($response->json())->toBeArray();
});

test('chat messages API return array', function () {
    $user   = User::factory()->create();
    $parent = ParentModel::create([
        'name'     => 'Test Parent',
        'email'    => 'parent@test.com',
        'password' => bcrypt('password'),
        'phone'    => '08123456789',
        'role'     => 'ortu',
    ]);

    $response = $this->actingAs($user)->getJson("/chat/{$parent->id}/messages");
    $response->assertStatus(200);
});