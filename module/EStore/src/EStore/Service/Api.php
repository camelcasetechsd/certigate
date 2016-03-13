<?php

namespace EStore\Service;

use Zend\Http\Request;
use Zend\Http\Client;
use EStore\Service\ApiCalls;

/**
 * Api Service
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Doctrine\DBAL\Driver\Connection $connection
 * @property string $serverBaseUrl
 * @property string $token
 * 
 * @package estore
 * @subpackage service
 */
class Api
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var Doctrine\DBAL\Driver\Connection
     */
    protected $connection;

    /**
     *
     * @var string
     */
    protected $serverBaseUrl;

    /**
     *
     * @var string
     */
    protected static $token;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query, $serverUrl)
    {
        $this->query = $query;
        $this->connection = $this->query->entityManager->getConnection();
        $this->serverBaseUrl = $serverUrl();
    }

    /**
     * Get estore api data
     * 
     * @access public
     * @return array api data
     */
    public function getApiData()
    {
        $apiEntries = $this->connection->fetchAll("select `name`,`key` from oc_api where status = 1 limit 1");
        return reset($apiEntries);
    }

    /**
     * Get api token via successful login with api key
     * 
     * @access public
     * @return string api token after successful login
     * @throws \Exception No valid token returned
     */
    public function getApiToken()
    {
        $request = new Request();
        $request->setUri($this->serverBaseUrl . ApiCalls::LOGIN);
        $request->setMethod(Request::METHOD_POST);

        $apiData = $this->getApiData();

        $request->getPost()->set('key', $apiData["key"]);

        $client = new Client();
        $loginResponse = $client->setEncType(Client::ENC_FORMDATA)->dispatch($request);

        if ($loginResponse->isSuccess()) {
            $logenResponseContent = json_decode($loginResponse->getContent());
            if (property_exists($logenResponseContent, "token")) {
                return self::$token = $logenResponseContent->token;
            }
        }
        throw new \Exception("No valid token returned");
    }

    /**
     * Make a call to an edge 
     * 
     * @access public
     * @param string $edge
     * @param string $method ,default is Request::METHOD_GET
     * @param array $parameters ,default is empty array
     * @param int $trialNumber current trial number ,default is 1
     * @return object response decoded content
     * @throws \Exception edge call failed
     * @throws \Exception trials limit reached
     */
    public function callEdge($edge, $method = Request::METHOD_GET, $parameters = array(), $trialNumber = 1)
    {
        $request = new Request();
        $request->setUri($this->serverBaseUrl . $edge);
        $request->setMethod($method);
        $client = new Client();
        
        // limit retiral to one time only after failure
        if($trialNumber === 3){
            throw new \Exception("trials limit reached");
        }
        
        // prepare request parameters container
        if ($method === Request::METHOD_GET) {
            $parametersContainer = $request->getQuery();
        }
        else {
            $parametersContainer = $request->getPost();
            $client->setEncType(Client::ENC_FORMDATA);
        }

        // fill request with query or post parameters
        foreach ($parameters as $parameterKey => $parameterValue) {
            $parametersContainer->set($parameterKey, $parameterValue);
        }
        
        // prepare token for call authentication 
        if(empty(self::$token)){
            $this->getApiToken();
        }
        $request->getQuery()->set("token", self::$token);
        
        $response = $client->dispatch($request);

        if ($response->isSuccess()) {
            $responseContent = json_decode($response->getContent());
            // assuming error is due to token expiry, retry with new token
            if (property_exists($responseContent, "error")) {
                $this->getApiToken();
                $responseContent = $this->callEdge($edge, $method, $parameters, ++$trialNumber);
            }
            return $responseContent;
        }
        throw new \Exception("$edge call failed");
    }

}
