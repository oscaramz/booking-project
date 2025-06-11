<?php

namespace App\Controllers;

use App\Repositories\BookingRepository;
use App\Services\BookingService;
use App\Core\Request;

class BookingController
{
    private BookingService $service;

    public function __construct(BookingService $service)
    {
        $this->service = $service;
    }

    public function create(): void
    {
        $data = Request::getJsonBody();

        if (empty($data['room_id']) || empty($data['start_datetime']) || empty($data['end_datetime']) || empty($data['client_name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing fields']);
            return;
        }

        try {
            $id = $this->service->createBooking(
                (int)$data['room_id'],
                $data['start_datetime'],
                $data['end_datetime'],
                $data['client_name']
            );

            echo json_encode(['id' => $id]);
        } catch (\Exception $e) {
            http_response_code(422);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
