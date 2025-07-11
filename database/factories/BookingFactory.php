<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory(),
            'user_id' => User::factory(),
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
        ];
    }
}