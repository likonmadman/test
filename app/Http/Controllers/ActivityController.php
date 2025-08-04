<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchByNameRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Activities")
 */
class ActivityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/activities",
     *     summary="Список видов деятельности",
     *     tags={"Activities"},
     *     security={{"apikey": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Activity")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return ActivityResource::collection(Activity::with('children')->get());
    }

    /**
     * @OA\Get(
     *     path="/api/activities/search",
     *     summary="Поиск деятельности по названию",
     *     tags={"Activities"},
     *     security={{"apikey": {}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название деятельности для поиска",
     *         @OA\Schema(type="string", example="Еда")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Activity")
     *         )
     *     )
     * )
     */
    public function search(SearchByNameRequest $request)
    {
        return ActivityResource::collection(
            Activity::where('name', 'like', '%' . $request->name . '%')->get()
        );
    }
}