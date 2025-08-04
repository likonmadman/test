<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Organization;
use App\Models\Phone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = Activity::all();

        $organizations = Organization::factory(20)->create();

        foreach ($organizations as $org) {
            // Привязка случайных видов деятельности
            $org->activities()->attach(
                $activities->random(random_int(1, 3))->pluck('id')->toArray()
            );

            // Добавляем телефоны
            Phone::factory(random_int(1, 3))->create([
                'organization_id' => $org->id,
            ]);
        }
    }
}
