<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApiIotTest extends TestCase
{
    /** @test */
    public function iot_ping_return_status_200()
    {
        $response = $this->getJson('/api/iot/ping');

        $response->assertStatus(200);
    }

    /** @test */
    public function iot_poll_return_status_200()
    {
        $response = $this->getJson('/api/iot/poll');

        $response->assertStatus(200);
    }
}