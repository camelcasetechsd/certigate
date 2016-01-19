<?php

namespace Versioning\Model;

/**
 * Version Model
 * 
 * Handles Version Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package versioning
 * @subpackage model
 */
class Version {

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
    public function __construct($query) {
        $this->query = $query;
    }

    /**
     * Prepare logs
     * 
     * @access public
     * @param mixed $entity
     * 
     * @return array array of prepared logs
     */
    public function prepareLogs($entity) {
        $logs = $this->query->setEntity('Gedmo\Loggable\Entity\LogEntry')->entityRepository->getLogEntries($entity);
        $preparedLogs = array();
        $logsKeys = array_keys($logs);
        $firstLogKey = reset($logsKeys);
        foreach($logs as $logKey => $log){
            $preparedLogs[] = array(
                'id' => $log->getId(),
                'action' => $log->getAction(),
                'loggedAt' => $log->getLoggedAt()->format("D, d M Y H:i"),
                'objectId' => $log->getObjectId(),
                'objectClass' => $log->getObjectClass(),
                'version' => $log->getVersion(),
                'data' => $log->getData(),
                'username' => $log->getUsername(),
                'isLast' => ($logKey == $firstLogKey)?true:false,
            );
        }
        
        return $preparedLogs;
    }


}
