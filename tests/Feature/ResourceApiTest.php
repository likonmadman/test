<?php

namespace Tests\Feature;

use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_resource(): void
    {
        $response = $this->postJson('/api/resources', [
            'name' => 'Test Room',
            'type' => 'room',
            'description' => 'Test description',
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'type',
                    'description',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('resources', ['name' => 'Test Room']);
    }

    public function test_can_list_resources(): void
    {
        $resources = Resource::factory()->count(3)->create();

        $response = $this->getJson('/api/resources');

        $response->assertOk();

        foreach ($resources as $resource) {
            $response->assertJsonFragment([
                'id' => $resource->id,
                'name' => $resource->name,
                'type' => $resource->type,
            ]);
        }
    }
}