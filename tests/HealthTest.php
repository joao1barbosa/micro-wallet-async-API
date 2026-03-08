<?php

namespace Tests;

class HealthTest extends TestCase
{
    public function test_health_endpoint_returns_ok()
    {
        $this->get('/health');

        $this->assertResponseOk();
        $this->seeJson(['status' => 'ok']);
    }
}
