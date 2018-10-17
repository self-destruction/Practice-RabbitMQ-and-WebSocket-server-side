<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/persisting_data.php';

use App\RabbitMQ\Consumer;

$dotenv = new Dotenv\Dotenv(__DIR__ . '\config');
$dotenv->load();

$consumer = new Consumer($_ENV['RABBITMQ_HOST'], $_ENV['RABBITMQ_PORT'], $_ENV['RABBITMQ_USER'], $_ENV['RABBITMQ_PASSWORD']);
$consumer->initChannel($_ENV['RABBITMQ_QUEUE_NAME']);
$consumer->initQueue(function ($msg) {
    echo " [x] Received $msg->body" . PHP_EOL;
//    var_dump(unserialize($msg->body));
    persistData(unserialize($msg->body));
});
while($consumer->countCallbacks()) {
    $consumer->wait();
}
$consumer->close();
