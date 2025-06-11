<?php

namespace App\Controllers;

use App\Repositories\RoomRepository;
use App\Core\Request;
use App\Core\AppException;


class RoomController
{
    private RoomRepository $repo;

    public function __construct(RoomRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create(): void
    {
        $data = Request::getJsonBody();

        if (empty($data['name']) || !isset($data['capacity'])) {
		    throw new AppException("Missing name or capacity", 400);
		}

		if ($data['capacity'] <= 0) {
		    throw new AppException("Capacity must be greater than 0", 422);
		}


        $id = $this->repo->create($data['name'], (int)$data['capacity']);
        echo json_encode(['id' => $id]);
    }

    public function list(): void
    {
        $rooms = $this->repo->findAll();
        echo json_encode($rooms);
    }
}
