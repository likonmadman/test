<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     summary="Create a new booking",
     *     tags={"Bookings"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"resource_id", "user_id", "start_time", "end_time"},
     *             @OA\Property(property="resource_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="start_time", type="string", format="date-time", example="2025-07-11T09:00:00Z"),
     *             @OA\Property(property="end_time", type="string", format="date-time", example="2025-07-11T10:00:00Z")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Booking created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreBookingRequest $request) : \Illuminate\Http\JsonResponse|BookingResource
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());
            return new BookingResource($booking);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/bookings/{id}",
     *     summary="Cancel a booking",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the booking to delete",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking canceled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Booking canceled successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found"
     *     )
     * )
     */
    public function destroy(int $id) : \Illuminate\Http\JsonResponse
    {
        $this->bookingService->cancelBooking($id);
        return response()->json(['message' => 'Booking canceled successfully.']);
    }

    /**
     * @OA\Get(
     *     path="/api/resources/{resourceId}/bookings",
     *     summary="Get all bookings for a specific resource",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         name="resourceId",
     *         in="path",
     *         description="ID of the resource",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of bookings",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Booking")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found"
     *     )
     * )
     */
    public function resourceBookings(int $resourceId) : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return BookingResource::collection($this->bookingService->getBookingsForResource($resourceId));
    }
}