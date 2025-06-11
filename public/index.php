<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\ErrorHandler;
use App\Core\Router;
use App\Controllers\RoomController;
use App\Controllers\BookingController;
use App\Controllers\AvailabilityController;
use App\Repositories\RoomRepository;
use App\Repositories\BookingRepository;
use App\Services\BookingService;
use App\Services\AvailabilityService;

// Démarre l'error handler
ErrorHandler::register();

// Connexion BDD
$pdo = require __DIR__ . '/../config/database.php';

// Instanciation des repos et services
$roomRepo = new RoomRepository($pdo);
$bookingRepo = new BookingRepository($pdo);
$bookingService = new BookingService($bookingRepo, $roomRepo);
$availabilityService = new AvailabilityService($roomRepo, $bookingRepo);

// Controllers
$roomController = new RoomController($roomRepo);
$bookingController = new BookingController($bookingService);
$availabilityController = new AvailabilityController($availabilityService);

// Router
$router = new Router();
$router->add('GET', '/rooms', [$roomController, 'list']);
$router->add('POST', '/rooms', [$roomController, 'create']);
$router->add('POST', '/bookings', [$bookingController, 'create']);
$router->add('GET', '/availability', [$availabilityController, 'listAvailableRooms']);
$router->add('GET', '/availability/check', [$availabilityController, 'check']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
