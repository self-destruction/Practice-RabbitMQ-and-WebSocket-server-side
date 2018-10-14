<?php

namespace App\RabbitMQ;

/**
 * Class Consumer
 * @package RabbitMQ\Consumer
 */
class Consumer extends RabbitMain {
    /**
     * Consumer constructor.
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $port, $user, $password) {
        parent::__construct($host, $port, $user, $password);
    }

    public function listenQueue(): void {
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $this->channel->basic_consume(
            $this->queueName,
            '',
            false,
            true,
            false,
            false,
            function ($msg) {
                echo ' [x] Received ', $msg->body, "\n";
            }
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}
