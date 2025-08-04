<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Корневые виды деятельности
        $food = Activity::create(['name' => 'Еда']);
        $auto = Activity::create(['name' => 'Автомобили']);

        // Подкатегории для "Еда"
        Activity::create(['name' => 'Мясная продукция', 'parent_id' => $food->id]);
        Activity::create(['name' => 'Молочная продукция', 'parent_id' => $food->id]);

        // Подкатегории для "Автомобили"
        Activity::create(['name' => 'Грузовые', 'parent_id' => $auto->id]);
        $light = Activity::create(['name' => 'Легковые', 'parent_id' => $auto->id]);

        // Подкатегории для "Легковые"
        Activity::create(['name' => 'Запчасти', 'parent_id' => $light->id]);
        Activity::create(['name' => 'Аксессуары', 'parent_id' => $light->id]);
    }
}
