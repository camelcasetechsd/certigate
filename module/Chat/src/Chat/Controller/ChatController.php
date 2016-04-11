<?php

namespace Chat\Controller;

use Utilities\Controller\ActionController;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class ChatController extends ActionController
{

    /**
     * console action to run Chat server
     */
    public function runServerAction()
    {
        $server = IoServer::factory(new HttpServer(new WsServer($this->getServiceLocator()->get('Chat\Service\ChatServer'))), 8080);
        $server->run();
    }

}
