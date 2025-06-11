<?php

use PHPUnit\Framework\TestCase;
use App\Services\BookingService;
use App\Repositories\BookingRepository;
use App\Repositories\RoomRepository;
use App\Core\AppException;

class BookingServiceTest extends TestCase
{
    private BookingService $service;
    private $bookingRepo;
    private $roomRepo;

    protected function setUp(): void
    {
        $this->bookingRepo = $this->createMock(BookingRepository::class);
        $this->roomRepo = $this->createMock(RoomRepository::class);
        $this->service = new BookingService($this->bookingRepo, $this->roomRepo);
    }

    public function testBookingThrowsExceptionIfDatesInvalid(): void
    {
        $this->roomRepo->method('find')->willReturn(['id' => 1]);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage("Start must be before end");

        $this->service->createBooking(1, "2024-07-01 12:00:00", "2024-07-01 10:00:00", "Oscar");
    }

    public function testBookingThrowsExceptionOnConflict(): void
    {
        $this->roomRepo->method('find')->willReturn(['id' => 1]);
        $this->bookingRepo->method('hasConflict')->willReturn(true);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage("Booking conflict");

        $this->service->createBooking(1, "2024-07-01 10:00:00", "2024-07-01 11:00:00", "Oscar");
    }

    public function testBookingSuccessfullyCreated(): void
    {
        $this->roomRepo->method('find')->willReturn(['id' => 1]);
        $this->bookingRepo->method('hasConflict')->willReturn(false);
        $this->bookingRepo->method('create')->willReturn(42);

        $result = $this->service->createBooking(1, "2024-07-01 10:00:00", "2024-07-01 11:00:00", "Oscar");

        $this->assertEquals(42, $result);
    }
}
