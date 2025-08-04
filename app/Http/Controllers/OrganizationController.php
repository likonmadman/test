<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchByRadiusRequest;
use App\Http\Resources\OrganizationResource;
use App\Services\OrganizationService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Organizations")
 */
class OrganizationController extends Controller
{
    public function __construct(private readonly OrganizationService $organizationService) {}

    /**
     * @OA\Get(
     *     path="/api/organizations/building/{buildingId}",
     *     summary="Список организаций в здании",
     *     tags={"Organizations"},
     *     security={{"apikey": {}}},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         required=true,
     *         description="ID здания",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="ООО Рога и Копыта"),
     *                 @OA\Property(property="phones", type="array", @OA\Items(type="string", example="8-923-666-13-13")),
     *                 @OA\Property(property="building", type="string", example="г. Москва, ул. Ленина 1"),
     *                 @OA\Property(
     *                     property="activities",
     *                     type="array",
     *                     @OA\Items(type="string", example="Молочная продукция")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function byBuilding(int $buildingId)
    {
        return OrganizationResource::collection($this->organizationService->getByBuilding($buildingId));
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/activity/{activityId}",
     *     summary="Список организаций по виду деятельности",
     *     tags={"Organizations"},
     *     security={{"apikey": {}}},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         description="ID вида деятельности",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     )
     * )
     */
    public function byActivity(int $activityId)
    {
        return OrganizationResource::collection($this->organizationService->getByActivity($activityId));
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/radius",
     *     summary="Список организаций в радиусе",
     *     tags={"Organizations"},
     *     security={{"apikey": {}}},
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         required=true,
     *         description="Широта точки поиска",
     *         @OA\Schema(type="number", format="float", example=55.7558)
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         required=true,
     *         description="Долгота точки поиска",
     *         @OA\Schema(type="number", format="float", example=37.6173)
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         required=true,
     *         description="Радиус поиска в километрах",
     *         @OA\Schema(type="number", format="float", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     )
     * )
     */
    public function byRadius(SearchByRadiusRequest $request)
    {
        return OrganizationResource::collection(
            $this->organizationService->getByRadius($request->lat, $request->lng, $request->radius)
        );
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Информация об организации",
     *     tags={"Organizations"},
     *     security={{"apikey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID организации",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     )
     * )
     */
    public function show(int $id)
    {
        return new OrganizationResource($this->organizationService->findById($id));
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/search",
     *     summary="Поиск организации по имени",
     *     tags={"Organizations"},
     *     security={{"apikey": {}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название организации (часть или полное)",
     *         @OA\Schema(type="string", example="Рога и Копыта")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     )
     * )
     */
    public function search(Request $request)
    {
        return OrganizationResource::collection(
            $this->organizationService->searchByName($request->query('name'))
        );
    }
}