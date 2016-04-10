<?php

namespace Chat\Service;

use ServiceLocatorFactory\ServiceLocatorFactory;
use Chat\Service\ConnectionParamters;

class ChatHandler
{

    /**
     *
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     *
     * @var Chat\Model\Chat
     */
    protected $chatModel;

    public function __construct()
    {
        $this->serviceManager = ServiceLocatorFactory::getInstance();
        $this->chatModel = $this->serviceManager->get('Chat\Model\Chat');
    }

    /**
     * function to save messages
     * @param type $jsonMessage
     */
    public function saveMessage($jsonMessage)
    {
        $message = json_decode($jsonMessage);
        $this->chatModel->saveMessage($message);
    }

    /**
     * function checks if client is Admin 
     * @param type $client
     * @return boolean
     */
    public function isAdmin($client)
    {
        if ($client->isAdmin) {
            return true;
        }
        return false;
    }

    /**
     * function return curent online admins
     * @param array $clients
     * @return array
     */
    public function getOnlineAdmins($clients, $parameters)
    {
        $admins = array();
        $index = 0;
        foreach ($clients as $client) {
            // execlude current user if admin 
            if ($client->isAdmin && $client->userId != $parameters[ConnectionParamters::USER_ID_TEXT]) {
                $admins[$index][ConnectionParamters::USER_ID_TEXT] = $client->userId;
                $admins[$index][ConnectionParamters::USERNAME_TEXT] = $client->username;
                $index++;
            }
        }
        return $admins;
    }

    /**
     * adding parameters to user connection
     * @param ConnectionInterface $conn
     * @param array $parameters
     * @return ConnectionInterface
     */
    public function prepareClientConnection($conn, $parameters)
    {
        return $this->chatModel->prepareConnection($conn, $parameters);
    }

    /**
     * function to return the specific recipient based on a key in message
     * @param type $clients
     * @param type $message
     */
    public function findRecipient($clients, $message)
    {
        $decodedMessage = json_decode($message);
        foreach ($clients as $client) {
            if ($client->userId == $decodedMessage->recipientId) {
                return $client;
            }
        }
    }

    /**
     * adding parameters to the message before sending it again
     * @param type $from
     * @param type $message
     */
    public function enhanceMsg($from, $message)
    {
        $msg = json_decode($message);
        // checking if the message from an admin or from a normal user 
        // to know the status of the sender
        if ($from->isAdmin) {
            var_dump('isAdmin');
            $msg->isAdmin = true;
        }
        else {
            $msg->isAdmin = false;
        }
        return json_encode($msg);
    }

}
