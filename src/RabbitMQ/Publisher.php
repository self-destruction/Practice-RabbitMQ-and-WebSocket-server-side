<?php

namespace App\RabbitMQ;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Publisher
 */
class Publisher extends RabbitMain {
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

    /**
     * @param string $message
     * @return AMQPMessage
     */
    public static function createMessage(string $message): AMQPMessage {
        return new AMQPMessage($message);
    }

    /**
     * @param AMQPMessage $message
     */
    public function send(AMQPMessage $message): void {
        $this->channel->basic_publish($message, '', $this->queueName);

        echo " [x] Sent $message->body" . PHP_EOL;
    }
}
