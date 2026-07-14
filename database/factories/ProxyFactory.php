<?php

namespace Database\Factories;

use App\Enums\ProxyProtocol;
use App\Models\Proxy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proxy>
 */
class ProxyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'host' => fake()->ipv4(),
            'port' => fake()->numberBetween(1024, 65535),
            'protocol' => fake()->randomElement(ProxyProtocol::cases())->value,
            'username' => null,
            'password' => null,
        ];
    }
}
