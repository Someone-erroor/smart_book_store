<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

try {
    $client = new Client("mongodb://127.0.0.1:27017");

    // Test the MongoDB connection
    $client->selectDatabase("smart_bookstore")->command([
        "ping" => 1
    ]);

    $db = $client->selectDatabase("smart_bookstore");

    $dbConnected = true;

} catch (Exception $e) {

    $dbConnected = false;
    $dbError = $e->getMessage();

}