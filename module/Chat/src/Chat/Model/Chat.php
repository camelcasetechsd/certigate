<?php

namespace Chat\Model;

use Chat\Entity\Message;
use Users\Entity\Role;

class Chat
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }
    /**
     * function saves sent message 
     * @param type $message
     */
    public function saveMessage($message)
    {
        $messageObj = new Message();

        $sender = $this->query->findOneBy('Users\Entity\User', array(
            'id' => $message->userId
        ));

        $recipient = $this->query->findOneBy('Users\Entity\User', array(
            'id' => $message->recipientId
        ));

        $messageObj->setText($message->text)
                ->setSender($sender)
                ->setRecipient($recipient)
                ->setCreated();

        $this->query->setEntity('Chat\Entity\Message')->save($messageObj);
    }
    /**
     * function adds parameters to Ratchet Websocket connection
     * @param ConnectionInterface $conn
     * @param array $userParameters
     * @return ConnectionInterface
     */
    public function prepareConnection($conn, $userParameters)
    {
        $conn->userId = $userParameters['userId'];
        $conn->username = $userParameters['username'];
        $conn->isAdmin = $this->isAdmin($userParameters['userId']);
        return $conn;
    }
    /**
     * checks if connected user has admin role or not
     * @param int $userId
     * @return boolean
     */
    private function isAdmin($userId)
    {
        $userRoles = $this->query->findOneBy('Users\Entity\User', array(
                    'id' => $userId
                ))->getRoles();

        foreach ($userRoles as $role) {
            if ($role->getName() == Role::ADMIN_ROLE) {
                return true;
            }
        }
        return false;
    }

}
