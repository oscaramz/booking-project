<?php

namespace App\Models;

class Room
{
    public int $id;
    public string $name;
    public int $capacity;

    public function __construct(int $id, string $name, int $capacity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->capacity = $capacity;
    }
}
