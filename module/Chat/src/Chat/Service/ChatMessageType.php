<?php

namespace Chat\Service;

class ChatMessageType
{

    /**
     * kind of messages between users 
     */
    const USER_MESSAGE_TEXT = "user_message";

    /**
     * kind of messages between user and server
     */
    const UPDATE_ADMINS_TEXT = "update_admins";

}
