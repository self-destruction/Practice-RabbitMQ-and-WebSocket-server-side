<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/extracting_data.php';

use App\RabbitMQ\Publisher;
use App\Formatter;
use React\EventLoop\Factory;
use React\Socket\Connector;
use React\Socket\ConnectionInterface;

$dotenv = new Dotenv\Dotenv(__DIR__ . '\config');
$dotenv->load();

$data = Formatter::convertData(extractData());

do {
    echo '1. Передача данных с помощью RabbitMQ.' . PHP_EOL;
    echo '2. Передача данных с помощью сокетов.' . PHP_EOL;
    echo '3. Выход.' . PHP_EOL;
    echo 'Введите команду: ';
    $line = readline();

    switch ($line) {
        case '1':
            $publisher = new Publisher($_ENV['RABBITMQ_HOST'], $_ENV['RABBITMQ_PORT'], $_ENV['RABBITMQ_USER'], $_ENV['RABBITMQ_PASSWORD']);
            $publisher->initChannel($_ENV['RABBITMQ_QUEUE_NAME']);

            $message = Publisher::createMessage($data);
            $publisher->send($message);

            $publisher->close();

            break;
        case '2':
            $loop = Factory::create();
            $connector = new Connector($loop);

            $connector->connect($_ENV['SERVER_URL'])
                ->then(function (ConnectionInterface $conn) use ($loop, $data) {
                    echo " [x] Sent $data" . PHP_EOL;
                    $conn->write($data);
                });

            $loop->run();

            break;
        default:
            break;
    }
    echo PHP_EOL;
} while ($line !== '3');
