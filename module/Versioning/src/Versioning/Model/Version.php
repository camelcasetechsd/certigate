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
     * 
     * @return array array of $entities' logs
     */
    public function getLogEntriesPerEntities($entities = array(), $objectIds = array(), $objectClass = null, $status = null)
    {
        $logsGroupedByObjectId = array();
        $logRepository = $this->query->setEntity('Versioning\Entity\LogEntry')->entityRepository;

        if (!(count($entities) == 0 && count($objectIds) == 0)) {
            $logs = $logRepository->getLogEntries($entities, $objectIds, $objectClass, $status);
            foreach ($logs as $log) {
                $logsGroupedByObjectId[$log->getObjectId()][] = $log;
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
     * @return array array of entities diff
     */
    public function prepareDiffs($entities, $entitiesLogs)
    {
        $entitiesComparisonData = array();
        foreach ($entitiesLogs as $logsPerEntity) {
            foreach ($logsPerEntity as $entityLog) {
                foreach ($entities as $entity) {
                    if ($entity->getId() == $entityLog->getObjectId()) {
                        $entitiesComparisonData[] = array(
                            "before" => $entity->getArrayCopy(),
                            "after" => $entityLog->getData(),
                        );
                        break;
                    }
                }
            }
        }
        foreach ($entitiesComparisonData as &$entityComparisonData) {
            foreach ($entityComparisonData["before"] as $objectPropertyName => $objectPropertyValue) {
                if ($entityComparisonData["before"]["status"] != Status::STATUS_NOT_APPROVED && !(is_object($objectPropertyValue) && !$objectPropertyValue instanceof \DateTime) && !(isset($entityComparisonData["after"][$objectPropertyName]) && $objectPropertyValue != $entityComparisonData["after"][$objectPropertyName])
                ) {
                    unset($entityComparisonData["before"][$objectPropertyName]);
                    unset($entityComparisonData["after"][$objectPropertyName]);
                }
            }
        }
        return new \ArrayIterator($entitiesComparisonData);
    }

}
