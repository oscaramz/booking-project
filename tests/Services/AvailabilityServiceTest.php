<?php

use PHPUnit\Framework\TestCase;
use App\Services\AvailabilityService;
use App\Repositories\BookingRepository;
use App\Repositories\RoomRepository;
use App\Core\AppException;

class AvailabilityServiceTest extends TestCase
{
    private AvailabilityService $service;
    private $roomRepo;
    private $bookingRepo;

    protected function setUp(): void
    {
        $this->roomRepo = $this->createMock(RoomRepository::class);
        $this->bookingRepo = $this->createMock(BookingRepository::class);
        $this->service = new AvailabilityService($this->roomRepo, $this->bookingRepo);
    }

    public function testRoomIsAvailable(): void
    {
        $this->roomRepo->method('find')->willReturn(['id' => 1]);
        $this->bookingRepo->method('isRoomAvailable')->willReturn(true);

        $available = $this->service->isRoomAvailable(1, "2024-07-01 10:00:00", "2024-07-01 11:00:00");
        $this->assertTrue($available);
    }

    public function testRoomIsNotAvailable(): void
    {
        $this->roomRepo->method('find')->willReturn(['id' => 1]);
        $this->bookingRepo->method('isRoomAvailable')->willReturn(false);

        $available = $this->service->isRoomAvailable(1, "2024-07-01 10:00:00", "2024-07-01 11:00:00");
        $this->assertFalse($available);
    }

    public function testRoomDoesNotExist(): void
    {
        $this->roomRepo->method('find')->willReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage("Room does not exist");

        $this->service->isRoomAvailable(99, "2024-07-01 10:00:00", "2024-07-01 11:00:00");
    }
}
