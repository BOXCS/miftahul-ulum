<?php

test('iot ping return status 200', function () {
    $response = $this->getJson('/api/iot/ping');

    $response->assertStatus(200);
});

test('iot poll return status 200', function () {
    $response = $this->getJson('/api/iot/poll');

    $response->assertStatus(200);
});