<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/extracting_data.php';

use App\RabbitMQ\Publisher;

$dotenv = new Dotenv\Dotenv(__DIR__ . '\config');
$dotenv->load();

$publisher = new Publisher($_ENV['RABBITMQ_HOST'], $_ENV['RABBITMQ_PORT'], $_ENV['RABBITMQ_USER'], $_ENV['RABBITMQ_PASSWORD']);
$publisher->initChannel($_ENV['RABBITMQ_QUEUE_NAME']);

$data = extractData();
$message = Publisher::createMessage(serialize($data));
//var_dump($data);
$publisher->send($message);

$publisher->close();
