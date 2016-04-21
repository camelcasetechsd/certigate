<?php

namespace Chat\Service;

class ServerMessages
{   
    /**
     * message sent by server when user has been disconnected
     * and appended to another recipients chat box if they has any 
     * live conversation with this user
     */
    const DISCONNECTED_USER_MESSAGE_TEXT = "This user has been disconnected";
}
