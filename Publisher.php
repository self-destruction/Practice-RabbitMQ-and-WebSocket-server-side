<?php

require __DIR__ . '/vendor/autoload.php';

use App\RabbitMQ\Publisher;

$publisher = new Publisher('192.168.99.100', 32771, 'guest', 'guest');
$publisher->initChannel('hello');
$publisher->send(Publisher::createMessage('Hello world!'));
$publisher->close();
