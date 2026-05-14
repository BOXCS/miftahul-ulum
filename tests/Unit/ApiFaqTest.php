<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiFaqTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function faq_endpoint_return_status_200()
    {
        $response = $this->getJson('/api/faq');

        $response->assertStatus(200);
    }

    /** @test */
    public function faq_detail_return_404_jika_tidak_ada()
    {
        $response = $this->getJson('/api/faq/99999');

        $response->assertStatus(404);
    }
}