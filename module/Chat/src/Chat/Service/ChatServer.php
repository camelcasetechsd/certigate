<?php

namespace Chat\Service;

use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Ratchet\ConnectionInterface;
use Chat\Service\ChatMessageType;

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

    public function __construct($chatHandler)
    {
        /**
         * to store connection object for each clinet
         */
        $this->clients = new SplObjectStorage;
        // handle messages ops
        $this->chatHandler = $chatHandler;
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
        $message = json_decode($msg);

        switch ($message->type) {
            //user to user message
            case ChatMessageType::USER_MESSAGE_TEXT:
                // save the message
                $this->chatHandler->saveMessage($msg);
                // find requested recipient
                $recipient = $this->chatHandler->findRecipient($this->clients, $message);
                // adding parameters to message before send again
                $enhancedMsg = $this->chatHandler->enhanceMsg($from, $message);
                if ($recipient) {
                    // send the message if user is still online
                    $recipient->send($enhancedMsg);
                }
                else {
                    $from->send($this->chatHandler->getClosedMessage);
                }
                break;
            //user to server message to update online admins
            case ChatMessageType::UPDATE_ADMINS_TEXT:
                $parameters = array(
                    'username' => $message->user,
                    'userId' => $message->userId
                );
                $onlineAdmins = $this->chatHandler->getOnlineAdmins($this->clients, $parameters);
                // sending online admins to client
                $from->send(json_encode(array('server' => $onlineAdmins)));
                break;
        }
    }

}
