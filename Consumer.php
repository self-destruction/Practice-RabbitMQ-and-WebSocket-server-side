<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/persisting_data.php';

use App\RabbitMQ\Consumer;

const QUEUE_NAME = 'rabbit_queue';

$consumer = new Consumer('192.168.99.100', 32771, 'guest', 'guest');
$consumer->initChannel(QUEUE_NAME);
$consumer->initQueue(function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    persistData(unserialize($msg->body));
});
while($consumer->countCallbacks()) {
    $consumer->wait();
}
$consumer->close();
