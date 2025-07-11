<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository
{
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function findById(int $id): Booking
    {
        return Booking::findOrFail($id);
    }

    public function delete(Booking $booking): void
    {
        $booking->delete();
    }

    public function getBookingsForResource(Resource $resource): Collection
    {
        return $resource->bookings()->with('resource')->get();
    }

    public function hasConflictingBookings(Resource $resource, string $start, string $end): bool
    {
        return $resource->bookings()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($query) use ($start, $end) {
                        $query->where('start_time', '<', $start)
                            ->where('end_time', '>', $end);
                    });
            })
            ->exists();
    }
}