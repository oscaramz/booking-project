<?php

namespace App\Controllers;

use App\Services\AvailabilityService;

class AvailabilityController
{
    private AvailabilityService $service;

    public function __construct(AvailabilityService $service)
    {
        $this->service = $service;
    }

    public function check(): void
    {
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        $roomId = $_GET['room_id'] ?? null;

        if (!$start || !$end || !$roomId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        $available = $this->service->isRoomAvailable((int)$roomId, $start, $end);
        echo json_encode(['available' => $available]);
    }

    public function listAvailableRooms(): void
    {
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;

        if (!$start || !$end) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        $availableRooms = $this->service->getAvailableRooms($start, $end);
        echo json_encode($availableRooms);
    }
}
