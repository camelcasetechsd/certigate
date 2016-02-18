<?php

namespace Versioning\Model;

use Utilities\Service\Status;

/**
 * Version Model
 * 
 * Handles Version Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * @property Utilities\Service\Object $objectUtilities
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
     *
     * @var Utilities\Service\Object 
     */
    protected $objectUtilities;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param Utilities\Service\Object $objectUtilities
     */
    public function __construct($query, $objectUtilities)
    {
        $this->query = $query;
        $this->objectUtilities = $objectUtilities;
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
        $logRepository = $this->query->setEntity('Versioning\Entity\LogEntry')->entityRepository;
        $logs = $logRepository->getLogEntries(array($entity));
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
     * @param array $entities ,default is empty array
     * @param array $objectIds ,default is empty array
     * @param string $objectClass ,default is null
     * @param int $status ,default is null
     * @param bool $allVersionsFlag ,default is false
     * 
     * @return array array of $entities' logs
     */
    public function getLogEntriesPerEntities($entities = array(), $objectIds = array(), $objectClass = null, $status = null, $allVersionsFlag = false)
    {
        $logsGroupedByObjectId = array();
        $logRepository = $this->query->setEntity('Versioning\Entity\LogEntry')->entityRepository;

        if (!(count($entities) == 0 && count($objectIds) == 0)) {
            $logs = $logRepository->getLogEntries($entities, $objectIds, $objectClass, $status);
            foreach ($logs as $log) {
                if ($allVersionsFlag === true || ( $allVersionsFlag === false && !array_key_exists($log->getObjectId(), $logsGroupedByObjectId))) {
                    $logsGroupedByObjectId[$log->getObjectId()][] = $log;
                }
            }
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
    public function getNotApprovedDataApprovedVersions($logsGroupedByObjectId, $statusProperty = "status", $activeStatusValue = Status::STATUS_ACTIVE)
    {
        $notApprovedDataApprovedVersions = array();
        foreach ($logsGroupedByObjectId as $objectId => $logsPerObjectId) {
            foreach ($logsPerObjectId as $log) {
                $versionData = $log->getData();
                if (isset($versionData[$statusProperty]) && $versionData[$statusProperty] == $activeStatusValue) {
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
    public function getApprovedDataForNotApprovedOnes(&$notApprovedData, $notApprovedDataApprovedVersions)
    {
        foreach ($notApprovedData as $notApprovedEntityKey => $notApprovedEntity) {
            $objectId = $notApprovedEntity->getId();
            if (!array_key_exists($objectId, $notApprovedDataApprovedVersions)) {
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
    public function getApprovedDataForNotApprovedOnesWrapper(&$notApprovedData)
    {
        if (count($notApprovedData) > 0) {
            $logsGroupedByObjectId = $this->getLogEntriesPerEntities($notApprovedData);
            $notApprovedDataApprovedVersions = $this->getNotApprovedDataApprovedVersions($logsGroupedByObjectId);
            $this->getApprovedDataForNotApprovedOnes($notApprovedData, $notApprovedDataApprovedVersions);
        }
    }

    /**
     * Prepare entities diffs
     * 
     * @access public
     * @param array $entities
     * @param array $entitiesLogs
     * 
     * @return \ArrayIterator array of entities diff
     */
    public function prepareDiffs($entities, $entitiesLogs)
    {
        $preparedEntities = $this->objectUtilities->prepareForDisplay($entities);
        $entitiesComparisonData = array();
        foreach ($preparedEntities as $entity) {
            $entityBeforeClass = get_class($entity);
            $entityHasChanges = false;
            foreach ($entitiesLogs as $logsPerEntity) {
                foreach ($logsPerEntity as $entityLog) {

                    if ($entity->getId() == $entityLog->getObjectId()) {
                        $entityAfterArray = $this->objectUtilities->prepareForDisplay(/* $objectsArray = */ array($entityLog->getData()), $entity);
                        $entityBefore = $entity;
                        if (method_exists($entity, /* $method_name = */ "getStatus") && $entity->getStatus() == Status::STATUS_NOT_APPROVED) {
                            // get empty object to compare after data with empty before
                            $entityBefore = new $entityBeforeClass;
                            $entityBefore->id = $entity->getId();
                        }
                        $entitiesComparisonData[] = array(
                            "before" => $entityBefore,
                            "after" => reset($entityAfterArray),
                        );
                        $entityHasChanges = true;
                        break 2;
                    }
                }
            }
            // add unchanged entity to comparison data 
            if ($entityHasChanges === false) {
                $entitiesComparisonData[] = array(
                    "before" => $entity,
                    "after" => $entity,
                );
            }
        }
        return new \ArrayIterator($entitiesComparisonData);
    }

    /**
     * Approve entities changes
     * 
     * @access public
     * @param array $entities
     * @param array $entitiesLogs
     * 
     * @return bool process result
     */
    public function approveChanges($entities, $entitiesLogs)
    {
        $processResult = true;
        foreach ($entities as $entity) {
            foreach ($entitiesLogs as $logsPerEntity) {
                foreach ($logsPerEntity as $entityLog) {
                    if ($entity->getId() == $entityLog->getObjectId()) {
                        $entityClass = get_class($entity);
                        $data = $entityLog->getData();
                        $data["status"] = Status::STATUS_ACTIVE;
                        $this->query->setEntity($entityClass)->save($entity, $data, /*$flushAll =*/ false, /* $noFlush = */ true);
                        break 2;
                    }
                }
            }
        }
        if(count($entities) > 0 && count($entitiesLogs) > 0 ){
            // flush all entities changes
            $this->query->entityManager->flush();
        }
        return $processResult;
    }

}
