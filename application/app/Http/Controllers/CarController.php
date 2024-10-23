<?php

namespace App\Http\Controllers;

use App\Http\Resources\CarResource;
use App\Models\Car;

class CarController extends Controller
{
    public function index() : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $cars = Car::all();

        return CarResource::collection($cars)->additional([
            'meta' => [
                'total_count' => $cars->count(),
            ],
        ]);
    }
}
