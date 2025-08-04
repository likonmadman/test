<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\BuildingResource;
use App\Services\BuildingService;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Buildings")
 */
class BuildingController extends Controller
{
    public function __construct(private readonly BuildingService $buildingService) {}

    /**
     * @OA\Get(
     *     path="/api/buildings",
     *     summary="Список зданий",
     *     tags={"Buildings"},
     *     security={{"apikey": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Building")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return BuildingResource::collection($this->buildingService->getAll());
    }
}