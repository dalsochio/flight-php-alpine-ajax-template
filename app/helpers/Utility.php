<?php

namespace App\helpers;

use GuzzleHttp\Client;
use Flight;
use Rakit\Validation\Validator;

class Utility
{
    static public function isViteRunning(): bool
    {
        try {
            // create a new http client for making requests
            $client = Utility::HTTP();
            // attempt to connect to the vite development server
            $response = $client->get('http://localhost:5173/@vite/client');
            // get the http status code from the response
            $status = $response->getStatusCode();

            if ($status === 200) {
                // if status is 200 (OK), vite server is running
                return true;
            }
        } catch (\Exception $e) {
            // silently handle any connection errors
        }

        // return false if server is not running or connection failed
        return false;

    }

    static public function HTTP($options = []): Client
    {
        // initialize the http client with configured options
        return new Client($options);
    }
}
