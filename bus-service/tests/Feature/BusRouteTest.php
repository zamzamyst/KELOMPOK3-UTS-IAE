<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Bus;
use App\Models\BusRoute;

class BusRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_assign_bus_to_route(): void
    {
        $bus = Bus::factory()->create();
        $route = BusRoute::factory()->create();

        $response = $this->postJson("/api/routes/{$route->id}/assign-bus", ['bus_id' => $bus->id]);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $route->id,
                     'buses' => [
                         ['id' => $bus->id]
                     ]
                 ]);

        $this->assertDatabaseHas('bus_route', [
            'bus_id' => $bus->id,
            'route_id' => $route->id,
        ]);
    }
}