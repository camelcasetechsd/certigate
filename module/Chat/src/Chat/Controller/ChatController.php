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
     *
     * @var Zend\Session\Container 
     */
    protected $sessionContainer;

    /**
     * console action to run Chat server
     */
    public function runServerAction()
    {
        try{
            $server = IoServer::factory(new HttpServer(new WsServer($this->getServiceLocator()->get('Chat\Service\ChatServer'))), 10100);
            $server->run();
        } catch (\Exception $ex) {
            // it is already running 
        }
        
    }

    public function startChatAction()
    {
        $userSession = new Container('chat');
        $userSession->chatStarted = true;
        $userSession->minimized = false;
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            // return what ever you need
            $this->getResponse()->setContent(Json::encode(array('message' => $userSession->minimized)));
            return $this->getResponse();
        }
    }

    /**
     * ajax request when the user decides to minimize chat container
     */
    public function minimizeChatAction()
    {
        $userSession = new Container('chat');
        $userSession->chatStarted = true;
        !isset($userSession->minimized) ? $userSession->minimized = true : $userSession->minimized = !$userSession->minimized;
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            // return what ever you need
            $this->getResponse()->setContent(Json::encode(array('message' => 'success')));
            return $this->getResponse();
        }
    }

}
