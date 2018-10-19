<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/persisting_data.php';

use App\RabbitMQ\Consumer;
use App\Formatter;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\Socket\ConnectionInterface;

$dotenv = new Dotenv\Dotenv(__DIR__ . '\config');
$dotenv->load();

do {
    echo '1. Передача данных с помощью RabbitMQ.' . PHP_EOL;
    echo '2. Передача данных с помощью сокетов.' . PHP_EOL;
    echo '3. Выход.' . PHP_EOL;
    echo 'Введите команду: ';
    $line = readline();

    switch ($line) {
        case '1':
            $consumer = new Consumer($_ENV['RABBITMQ_HOST'], $_ENV['RABBITMQ_PORT'], $_ENV['RABBITMQ_USER'], $_ENV['RABBITMQ_PASSWORD']);
            $consumer->initChannel($_ENV['RABBITMQ_QUEUE_NAME']);
            $consumer->initQueue(function ($msg) {
                echo " [x] Received $msg->body" . PHP_EOL;
                $data = Formatter::revertConvertedData($msg->body);
                persistData($data);
            });
            while($consumer->countCallbacks()) {
                $consumer->wait();
            }
            $consumer->close();

            break;
        case '2':
            $loop = Factory::create();
            $socket = new Server($_ENV['SERVER_URL'], $loop);

            $socket->on('connection', function (ConnectionInterface $conn) {
                $conn->on('data', function ($data) use ($conn) {
                    echo " [x] Received $data" . PHP_EOL;
                    $data = Formatter::revertConvertedData($data);
                    persistData($data);
                    $conn->close();
                });
            });

            $loop->run();

            break;
        default:
            break;
    }
    echo PHP_EOL;
} while ($line !== '3');
