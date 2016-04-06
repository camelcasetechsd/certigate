<?php

namespace Chat\Service;

use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Ratchet\ConnectionInterface;


class ChatServer implements MessageComponentInterface
{

    /**
     * Stores connection objects for all clients
     * @var Array 
     */
    protected $clients;

    public function __construct()
    {
        /**
         * to store connection object for each clinet
         */
        $this->clients = new SplObjectStorage;
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "User has been Disconnected!\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * send message to all clients except the source
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        //send the message to all the other clients except the one who sent.
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onOpen(ConnectionInterface $conn)
    {
        //store the new connection
        $this->clients->attach($conn);
        echo "New User Connected!\n";
    }

}
