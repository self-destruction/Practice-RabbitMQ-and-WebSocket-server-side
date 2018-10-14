<?php

namespace App\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class RabbitMain
 */
class RabbitMain
{
    /**
     * @var string
     */
    protected $queueName;
    /**
     * @var AMQPStreamConnection
     */
    protected $connection;
    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * Consumer constructor.
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     */
    protected function __construct(
        $host = 'localhost',
        $port = 5672,
        $user = 'guest',
        $password = 'guest'
    ) {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
    }

    /**
     * @param string $queueName
     */
    protected function setQueueName(string $queueName): void {
        $this->queueName = $queueName;
    }

    /**
     * @param string $queueName
     */
    public function initChannel(string $queueName): void {
        $this->setQueueName($queueName);

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queueName, false, false, false, false);
    }

    public function close(): void {
        $this->channel->close();
        $this->connection->close();
    }
}
