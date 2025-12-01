<?php

namespace App\Config;

use Flight;

// get the flight application instance
$app = Flight::app();
// set the base url for the application
$app->set('flight.base_url', '/');
// enable error logging
$app->set('flight.log_errors', true);
// enable flight's error handling
$app->set('flight.handle_errors', true);
// set the path to view templates
$app->set('flight.views.path', __DIR__ . '/../views');
