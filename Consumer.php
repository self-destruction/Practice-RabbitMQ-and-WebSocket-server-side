<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\RabbitMQ\Consumer;

$consumer = new Consumer('192.168.99.100', 32771, 'guest', 'guest');
$consumer->initChannel('hello');
$consumer->listenQueue();
$consumer->close();
