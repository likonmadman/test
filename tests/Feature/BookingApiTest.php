<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_booking(): void
    {
        $resource = Resource::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson('/api/bookings', [
            'resource_id' => $resource->id,
            'user_id' => $user->id,
            'start_time' => now()->addHour()->toDateTimeString(),
            'end_time' => now()->addHours(2)->toDateTimeString(),
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => ['id', 'user_id', 'start_time', 'end_time', 'created_at', 'updated_at']
            ]);

        $this->assertDatabaseHas('bookings', ['resource_id' => $resource->id]);
    }

    public function test_can_get_bookings_for_resource(): void
    {
        $resource = Resource::factory()->create();
        $user = User::factory()->create();
        Booking::factory()->count(2)->create([
            'resource_id' => $resource->id,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/resources/{$resource->id}/bookings");

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_can_cancel_booking(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->deleteJson("/api/bookings/{$booking->id}");

        $response->assertOk()
            ->assertJson(['message' => 'Booking canceled successfully.']);

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_cannot_create_conflicting_booking(): void
    {
        $resource = Resource::factory()->create();
        $user = User::factory()->create();

        // Первое бронирование
        Booking::factory()->create([
            'resource_id' => $resource->id,
            'user_id' => $user->id,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
        ]);

        // Второе пересекающееся бронирование
        $response = $this->postJson('/api/bookings', [
            'resource_id' => $resource->id,
            'user_id' => $user->id,
            'start_time' => now()->addMinutes(90)->toDateTimeString(),
            'end_time' => now()->addHours(3)->toDateTimeString(),
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }
}