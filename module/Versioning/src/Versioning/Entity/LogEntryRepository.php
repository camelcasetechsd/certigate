<?php

namespace Versioning\Entity;

use Gedmo\Loggable\Entity\Repository\LogEntryRepository as OriginalLogEntryRepository;
use Doctrine\Common\Collections\Criteria;
use Gedmo\Tool\Wrapper\EntityWrapper;

/**
 * LogEntry Repository
 * 
 * @package versioning
 * @subpackage entity
 */
class LogEntryRepository extends OriginalLogEntryRepository
{

    /**
     * Get log entries
     * 
     * @access public
     * @param array $entities ,default is empty array
     * @param array $objectIds ,default is empty array
     * @param string $objectClass ,default is null
     * @param int $status ,default is null
     * @return array log entries array
     */
    public function getLogEntries($entities = array(), $objectIds = array(), $objectClass = null, $status = null)
    {
        $parameters = array();
        $queryBuilder = $this->createQueryBuilder("log");
        $queryBuilder->select("log")
                ->addOrderBy('log.version', Criteria::DESC)
                ->addGroupBy('log.objectId');

        if (count($entities) > 0) {
            // assuming array of entities belong to them class
            $entity = reset($entities);
            $objectIds = array();
            $wrapped = new EntityWrapper($entity, $this->getEntityManager());
            $objectClass = $wrapped->getMetadata()->name;
            // collect entitites ids
            array_shift($entities);
            $objectIds[] = $wrapped->getIdentifier();
            foreach ($entities as $entity) {
                $wrapped = new EntityWrapper($entity, $this->getEntityManager());
                $objectIds[] = $wrapped->getIdentifier();
            }
        }

        if (!is_null($status)) {
            $parameters["objectStatus"] = $status;
            $queryBuilder->andWhere($queryBuilder->expr()->eq('log.objectStatus', ":objectStatus"));
        }
        if (!is_null($objectClass)) {
            $parameters["objectClass"] = $objectClass;
            $queryBuilder->andWhere($queryBuilder->expr()->eq('log.objectClass', ":objectClass"));
        }
        if (count($objectIds) > 0) {
            $parameters["objectIds"] = $objectIds;
            $queryBuilder->andWhere($queryBuilder->expr()->in('log.objectId', ":objectIds"));
        }
        $queryBuilder->setParameters($parameters);

        return $queryBuilder->getQuery()->getResult();
    }

}
