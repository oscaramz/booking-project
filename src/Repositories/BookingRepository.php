<?php

namespace App\Repositories;

use PDO;

class BookingRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $roomId, string $start, string $end, string $clientName): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO bookings (room_id, start_datetime, end_datetime, client_name)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$roomId, $start, $end, $clientName]);
        return (int)$this->pdo->lastInsertId();
    }

    public function hasConflict(int $roomId, string $start, string $end): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM bookings
            WHERE room_id = ?
            AND start_datetime < ?
            AND end_datetime > ?
        ");
        $stmt->execute([$roomId, $end, $start]);
        return $stmt->fetchColumn() > 0;
    }

    public function isRoomAvailable(int $roomId, string $start, string $end): bool
	{
	    $stmt = $this->pdo->prepare("
	        SELECT COUNT(*) FROM bookings
	        WHERE room_id = ?
	        AND start_datetime < ?
	        AND end_datetime > ?
	    ");
	    $stmt->execute([$roomId, $end, $start]);
	    return $stmt->fetchColumn() == 0;
	}

}
