<?php

namespace Chat\Service;

use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Ratchet\ConnectionInterface;
use Chat\Service\ChatHandler;

class ChatServer implements MessageComponentInterface
{

    /**
     *
     * @var Chat\Service\ChatHandler
     */
    protected $chatHandler;

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
        // handle messages ops
        $this->chatHandler = new ChatHandler();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        //store the new connection
        $parameters = $conn->WebSocket->request->getQuery()->toArray();
        // adding parameters to user connection
        $preparedConn = $this->chatHandler->prepareClientConnection($conn, $parameters);
        // add connection to connections array
        $this->clients->attach($preparedConn);
        //getting online admins
        $onlineAdmins = $this->chatHandler->getOnlineAdmins($this->clients, $parameters);
        // sending online admins to client
        $conn->send(json_encode(array('server' => $onlineAdmins)));
        var_dump($onlineAdmins);
        echo "New User Connected!\n";
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
                $this->chatHandler->saveMessage($msg);
            }
        }
    }

}
