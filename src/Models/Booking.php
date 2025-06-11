<?php

namespace App\Models;

class Booking
{
    public int $id;
    public int $roomId;
    public string $startDatetime;
    public string $endDatetime;
    public string $clientName;

    public function __construct(int $id, int $roomId, string $start, string $end, string $clientName)
    {
        $this->id = $id;
        $this->roomId = $roomId;
        $this->startDatetime = $start;
        $this->endDatetime = $end;
        $this->clientName = $clientName;
    }
}
