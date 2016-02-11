<?php

namespace Versioning\Model;

use Gedmo\Tool\Wrapper\EntityWrapper;
use Doctrine\Common\Collections\Criteria;
use Utilities\Service\Status;

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
class Version
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
     * Prepare logs
     * 
     * @access public
     * @param mixed $entity
     * 
     * @return array array of prepared logs
     */
    public function prepareLogs($entity)
    {
        $logs = $this->query->setEntity('Gedmo\Loggable\Entity\LogEntry')->entityRepository->getLogEntries($entity);
        $preparedLogs = array();
        $logsKeys = array_keys($logs);
        $firstLogKey = reset($logsKeys);
        foreach ($logs as $logKey => $log) {
            $preparedLogs[] = array(
                'id' => $log->getId(),
                'action' => $log->getAction(),
                'loggedAt' => $log->getLoggedAt()->format("D, d M Y H:i"),
                'objectId' => $log->getObjectId(),
                'objectClass' => $log->getObjectClass(),
                'version' => $log->getVersion(),
                'data' => $log->getData(),
                'username' => $log->getUsername(),
                'isLast' => ($logKey == $firstLogKey) ? true : false,
            );
        }

        return $preparedLogs;
    }

    /**
     * Get logs for entries
     * 
     * @access public
     * @param array $entities
     * 
     * @return array array of $entities' logs
     */
    public function getLogEntriesPerEntities($entities)
    {
        // assuming array of entities belong to them class
        $entity = reset($entities);
        $objectIds = array();
        $wrapped = new EntityWrapper($entity, $this->query->entityManager);
        $objectClass = $wrapped->getMetadata()->name;
        // collect entitites ids
        array_shift($entities);
        $objectIds[] = $wrapped->getIdentifier();
        foreach ($entities as $entity) {
            $wrapped = new EntityWrapper($entity, $this->query->entityManager);
            $objectIds[] = $wrapped->getIdentifier();
        }

        $logRepository = $this->query->setEntity('Gedmo\Loggable\Entity\LogEntry')->entityRepository;
        $queryBuilder = $logRepository->createQueryBuilder("log");
        $parameters = compact('objectIds', 'objectClass');

        $queryBuilder->select("log")
                ->addOrderBy('log.version', Criteria::DESC)
                ->andWhere($queryBuilder->expr()->eq('log.objectClass', ":objectClass"))
                ->andWhere($queryBuilder->expr()->in('log.objectId', ":objectIds"))
                ->setParameters($parameters);

        $logs = $queryBuilder->getQuery()->getResult();
        
        $logsGroupedByObjectId = array();
        foreach($logs as $log){
            $logsGroupedByObjectId[$log->getObjectId()][] = $log;
        }
        return $logsGroupedByObjectId;
    }
    
    /**
     * Get approved versions for currently not approved entities
     * 
     * @access public
     * @param array $logsGroupedByObjectId
     * @param string $statusProperty ,default is status
     * @param string $activeStatusValue ,default is active status
     * @return array approved versions
     */
    public function getNotApprovedDataApprovedVersions($logsGroupedByObjectId, $statusProperty = "status", $activeStatusValue = Status::STATUS_ACTIVE){
        $notApprovedDataApprovedVersions = array();
        foreach($logsGroupedByObjectId as $objectId => $logsPerObjectId){
            foreach($logsPerObjectId as $log){
                $versionData = $log->getData();
                if(isset($versionData[$statusProperty]) && $versionData[$statusProperty] == $activeStatusValue){
                    $notApprovedDataApprovedVersions[$objectId] = $versionData;
                    break;
                }
            }
        }
        return $notApprovedDataApprovedVersions;
    }
    
    /**
     * Get approved data for not approved versions
     * 
     * @access public
     * @param array $notApprovedData
     * @param array $notApprovedDataApprovedVersions
     * @return array approved data versions for not approved ones
     */
    public function getApprovedDataForNotApprovedOnes(&$notApprovedData, $notApprovedDataApprovedVersions){
        foreach($notApprovedData as $notApprovedEntityKey => $notApprovedEntity){
            $objectId = $notApprovedEntity->getId();
            if(!array_key_exists($objectId, $notApprovedDataApprovedVersions)){
                unset($notApprovedData[$notApprovedEntityKey]);
                continue;
            }
            $notApprovedEntity->exchangeArray($notApprovedDataApprovedVersions[$objectId]);
        }
        return $notApprovedData;
    }
    
    /**
     * Get approved data for not approved data wrapper
     * 
     * @access public
     * @param array $notApprovedData
     * @return array approved data versions for not approved ones
     */
    public function getApprovedDataForNotApprovedOnesWrapper(&$notApprovedData){
        $logsGroupedByObjectId = $this->getLogEntriesPerEntities($notApprovedData);
        $notApprovedDataApprovedVersions = $this->getNotApprovedDataApprovedVersions($logsGroupedByObjectId);
        $this->getApprovedDataForNotApprovedOnes($notApprovedData,$notApprovedDataApprovedVersions);
    }

}
