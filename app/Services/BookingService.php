<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\ResourceRepository;
use App\Models\Booking;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(
        protected BookingRepository $bookingRepository,
        protected ResourceRepository $resourceRepository,
    ) {}

    public function createBooking(array $data): Booking
    {
        $resource = $this->resourceRepository->findById($data['resource_id']);

        if ($this->bookingRepository->hasConflictingBookings($resource, $data['start_time'], $data['end_time'])) {
            throw ValidationException::withMessages([
                'booking' => ['This resource is already booked for the selected time range.'],
            ]);
        }

        return $this->bookingRepository->create($data);
    }

    public function cancelBooking(int $bookingId): void
    {
        $booking = $this->bookingRepository->findById($bookingId);
        $this->bookingRepository->delete($booking);
    }

    public function getBookingsForResource(int $resourceId): Collection
    {
        $resource = $this->resourceRepository->findById($resourceId);
        return $this->bookingRepository->getBookingsForResource($resource);
    }
}