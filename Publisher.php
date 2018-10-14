<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/extracting_data.php';

use App\RabbitMQ\Publisher;

const QUEUE_NAME = 'rabbit_queue';

$publisher = new Publisher('192.168.99.100', 32771, 'guest', 'guest');
$publisher->initChannel(QUEUE_NAME);

$message = Publisher::createMessage(serialize(extractData()));
$publisher->send($message);

$publisher->close();
