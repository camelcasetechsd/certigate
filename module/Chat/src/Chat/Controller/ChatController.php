<?php

namespace Chat\Controller;

use Utilities\Controller\ActionController;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Zend\Json\Json;
use Zend\Session\Container; // We need this when using sessions

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

    public function startChatAction()
    {
        $userSession = new Container('chat');
        $userSession->chatStarted = true;
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            // return what ever you need
            $this->getResponse()->setContent(Json::encode(array('message' => 'success')));
            return $this->getResponse();
        }
    }

}
