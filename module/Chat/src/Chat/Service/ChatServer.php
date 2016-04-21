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
        $conn->send($this->chatHandler->getOnlineAdminsMessage($onlineAdmins));

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
                $recipients = $this->chatHandler->findRecipient($this->clients, $message);
                // adding parameters to message before send again
                $enhancedMsg = $this->chatHandler->enhanceMsg($from, $message);
                if ($recipients) {
                    // send the message if user is still online
                    // recipients are plural to overcome opening the same account fraud
                    foreach ($recipients as $recipient) {
                        $recipient->send($enhancedMsg);
                    }
                }
                else {
                    // return message user has been disconnected
                    // recipient is the disconnected person
                    $from->send($this->chatHandler->getClosedMessage($message->recipientId));
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
                $from->send($this->chatHandler->getOnlineAdminsMessage($onlineAdmins));
                break;
        }
    }

}
