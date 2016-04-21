<?php

namespace Chat\Service;

use Chat\Service\ConnectionParamters;
use Chat\Service\ServerMessages;
use Chat\Service\ChatMessageType;

class ChatHandler
{

    /**
     *
     * @var Chat\Model\Chat
     */
    protected $chatModel;

    public function __construct($chatModel)
    {
        $this->chatModel = $chatModel;
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
        $recipients = array();
        foreach ($clients as $client) {
            if ($client->userId == $message->recipientId) {
                array_push($recipients, $client);
            }
        }
        return $recipients;
    }

    /**
     * adding parameters to the message before sending it again
     * @param type $from
     * @param type $message
     */
    public function enhanceMsg($from, $message)
    {
        // checking if the message from an admin or from a normal user 
        // to know the status of the sender
        if ($from->isAdmin) {
            $message->isAdmin = true;
        }
        else {
            $message->isAdmin = false;
        }
        return json_encode($message);
    }

    public function getClosedMessage($userId)
    {
        return json_encode(array(
            'type' => ChatMessageType::USER_DISCONNECTED_TEXT,
            'server' => ServerMessages::DISCONNECTED_USER_MESSAGE_TEXT,
            'userId' => $userId
        ));
    }

    public function getOnlineAdminsMessage($onlineAdmins)
    {
        return json_encode(array(
            'type' => ChatMessageType::UPDATE_ADMINS_TEXT,
            'server' => $onlineAdmins
        ));
    }

}
