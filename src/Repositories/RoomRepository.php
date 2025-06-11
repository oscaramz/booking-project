<?php

namespace App\Repositories;

use PDO;

class RoomRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $name, int $capacity): int
	{
	    try {
	        $stmt = $this->pdo->prepare("INSERT INTO rooms (name, capacity) VALUES (?, ?)");
	        $stmt->execute([$name, $capacity]);
	        return (int)$this->pdo->lastInsertId();
	    } catch (\PDOException $e) {
	        if ($e->errorInfo[1] == 1062) { // code erreur MySQL duplicate entry
	            throw new \App\Core\AppException("Room name already exists", 422);
	        }
	        throw $e; // sinon on relance l'erreur normale
	    }
	}

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM rooms");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        return $room ?: null;
    }
}
