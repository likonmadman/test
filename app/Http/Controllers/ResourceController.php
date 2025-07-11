<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Http\Resources\ResourceResource;
use App\Services\ResourceService;
use OpenApi\Annotations as OA;

class ResourceController extends Controller
{
    public function __construct(protected ResourceService $resourceService) {}

    /**
     * @OA\Get(
     *     path="/api/resources",
     *     summary="Get list of resources",
     *     tags={"Resources"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Resource"))
     *     )
     * )
     */
    public function index() : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ResourceResource::collection($this->resourceService->getAll());
    }

    /**
     * @OA\Post(
     *     path="/api/resources",
     *     summary="Create a new resource",
     *     tags={"Resources"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "type"},
     *             @OA\Property(property="name", type="string", example="Room 101"),
     *             @OA\Property(property="type", type="string", example="room"),
     *             @OA\Property(property="description", type="string", example="Conference room with projector")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Resource created successfully")
     * )
     */
    public function store(StoreResourceRequest $request) : ResourceResource
    {
        $resource = $this->resourceService->create($request->validated());
        return new ResourceResource($resource);
    }
}