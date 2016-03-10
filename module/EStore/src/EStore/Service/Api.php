<?php

namespace EStore\Service;

/**
 * Api Service
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Doctrine\DBAL\Driver\Connection $connection
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
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
        $this->connection = $this->query->entityManager->getConnection();
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
    
    public function getApiToken()
    {
        
    }

}
