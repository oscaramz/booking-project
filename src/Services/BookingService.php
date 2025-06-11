<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\RoomRepository;
use App\Core\AppException;

class BookingService
{
    private BookingRepository $bookingRepo;
    private RoomRepository $roomRepo;

    public function __construct(BookingRepository $bookingRepo, RoomRepository $roomRepo)
    {
        $this->bookingRepo = $bookingRepo;
        $this->roomRepo = $roomRepo;
    }

    public function createBooking(int $roomId, string $start, string $end, string $clientName): int
    {
        if (strtotime($start) === false || strtotime($end) === false) {
            throw new AppException("Invalid date format", 422);
        }

        if (strtotime($start) >= strtotime($end)) {
            throw new AppException("Start must be before end", 422);
        }

        if (!$this->roomRepo->find($roomId)) {
            throw new AppException("Room does not exist", 404);
        }

        if ($this->bookingRepo->hasConflict($roomId, $start, $end)) {
            throw new AppException("Booking conflict", 422);
        }

        return $this->bookingRepo->create($roomId, $start, $end, $clientName);
    }
}
