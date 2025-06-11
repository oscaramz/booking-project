<?php

namespace App\Services;

use App\Repositories\RoomRepository;
use App\Repositories\BookingRepository;
use App\Core\AppException;

class AvailabilityService
{
    private RoomRepository $roomRepo;
    private BookingRepository $bookingRepo;

    public function __construct(RoomRepository $roomRepo, BookingRepository $bookingRepo)
    {
        $this->roomRepo = $roomRepo;
        $this->bookingRepo = $bookingRepo;
    }

    public function isRoomAvailable(int $roomId, string $start, string $end): bool
	{
	    if (!$this->roomRepo->find($roomId)) {
	        throw new AppException("Room does not exist", 404);
	    }

	    return $this->bookingRepo->isRoomAvailable($roomId, $start, $end);
	}

    public function getAvailableRooms(string $start, string $end): array
    {
        $rooms = $this->roomRepo->findAll();
        $available = [];

        foreach ($rooms as $room) {
            if ($this->bookingRepo->isRoomAvailable($room['id'], $start, $end)) {
                $available[] = $room;
            }
        }

        return $available;
    }
}
