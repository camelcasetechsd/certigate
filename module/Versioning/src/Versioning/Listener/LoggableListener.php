<?php

namespace Versioning\Listener;

use Gedmo\Loggable\LoggableListener as OriginalLoggableListener;
use Doctrine\Common\EventArgs;
use Gedmo\Tool\Wrapper\AbstractWrapper;
use Utilities\Service\Status;

/**
 * Loggable listener
 * 
 * Handles Entity changes logging related business
 *
 * @property bool $isAdminUser
 * @property Gedmo\Mapping\Event\AdapterInterface $eventAdapter
 * 
 * @package versioning
 * @subpackage listener
 */
class LoggableListener extends OriginalLoggableListener
{

    /**
     *
     * @var bool 
     */
    protected $isAdminUser;

    /**
     *
     * @var Gedmo\Mapping\Event\AdapterInterface 
     */
    protected $eventAdapter;

    /**
     * Set isAdminUser
     * 
     * @access public
     * @param bool $isAdminUser
     */
    public function setIsAdminUser($isAdminUser)
    {
        $this->isAdminUser = $isAdminUser;
    }

    /**
     * Handle any custom LogEntry functionality that needs to be performed
     * before persisting it
     *
     * @access protected
     * @param object $logEntry The LogEntry being persisted
     * @param object $object   The object being Logged
     */
    protected function prePersistLogEntry($logEntry, $object)
    {
        $objectManager = $this->eventAdapter->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $objectManager);
        $meta = $wrapped->getMetadata();
        $config = $this->getConfiguration($objectManager, $meta->name);
        $objectData = $object->getArrayCopy();
        $loggedData = $logEntry->getData();
        $unsetObjectData = array();
        foreach ($objectData as $fieldName => $fieldValue) {
            if (empty($config['versioned']) || !in_array($fieldName, $config['versioned'])) {
                continue;
            }
            if (!array_key_exists($fieldName, $loggedData) && in_array($fieldName, $config['versioned'])) {
                $unsetObjectData[$fieldName] = $fieldValue;
            }
        }

        $processedUnsetObjectData = $this->processObjectUnchangedData($unsetObjectData, $object);
        $logEntry->setData(array_merge($processedUnsetObjectData, $loggedData));
    }

    /**
     * Process an object's unchanged data
     *
     * @access protected
     * @param array $unchangedData
     * @param object $object
     *
     * @return array unchanged data
     */
    protected function processObjectUnchangedData($unchangedData, $object)
    {
        $objectManager = $this->eventAdapter->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $objectManager);
        $meta = $wrapped->getMetadata();
        $unchangedValues = array();

        foreach ($unchangedData as $field => $value) {
            if ($meta->isSingleValuedAssociation($field) && $value) {
                $wrappedAssoc = AbstractWrapper::wrap($value, $objectManager);
                $value = $wrappedAssoc->getIdentifier(false);
            }
            $unchangedValues[$field] = $value;
        }

        return $unchangedValues;
    }

    /**
     * Looks for loggable objects being inserted or updated
     * for further processing
     *
     * @access public
     * @param EventArgs $eventArgs
     *
     * @return void
     */
    public function onFlush(EventArgs $eventArgs)
    {
        $this->eventAdapter = $this->getEventAdapter($eventArgs);
        $entityManager = $eventArgs->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        foreach ($this->eventAdapter->getScheduledObjectInsertions($unitOfWork) as $object) {
            $this->createLogEntry(self::ACTION_CREATE, $object, $this->eventAdapter);
        }
        foreach ($this->eventAdapter->getScheduledObjectDeletions($unitOfWork) as $object) {
            $this->createLogEntry(self::ACTION_REMOVE, $object, $this->eventAdapter);
        }

        foreach ($this->eventAdapter->getScheduledObjectUpdates($unitOfWork) as $entity) {
            $entityChangeSet = $unitOfWork->getEntityChangeSet($entity);
            // on approval, delete old versions
            if (array_key_exists("status", $entityChangeSet) && reset($entityChangeSet["status"]) == Status::STATUS_NOT_APPROVED && end($entityChangeSet["status"]) != Status::STATUS_NOT_APPROVED) {
                $objectClass = get_class($entity);
                $logClass = $this->getLogEntryClass($this->eventAdapter, $objectClass);
                $parameters = array(
                    'objectId' => $entity->getId(),
                    'objectClass' => $objectClass,
                );
                $queryBuilder = $entityManager->createQueryBuilder();
                $queryBuilder->delete($logClass, "log")
                        ->andWhere($queryBuilder->expr()->eq('log.objectId', ":objectId"))
                        ->andWhere($queryBuilder->expr()->eq('log.objectClass', ":objectClass"))
                        ->setParameters($parameters)
                        ->getQuery()->execute();
            }
            else {
                // on editing by non-admin where entity is supposed to be not approved, do not update entity
                if (array_key_exists("status", $entityChangeSet) && reset($entityChangeSet["status"]) != Status::STATUS_NOT_APPROVED && end($entityChangeSet["status"]) == Status::STATUS_NOT_APPROVED) {
                    $entityManager->detach($entity);
                }
                $this->createLogEntry(self::ACTION_UPDATE, $entity, $this->eventAdapter);
            }
        }
    }

}
