<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test healtcheck route.
     *
     * @return void
     */
    public function testHealthckeckRouteTest()
    {
        $response = $this->get('/healthcheck');
        $response->assertStatus(200);
        $response->assertSeeText("template");
    }
}
