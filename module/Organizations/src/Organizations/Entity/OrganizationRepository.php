<?php

namespace Organizations\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Organization Repository
 * 
 * @package organizations
 * @subpackage entity
 */
class OrganizationRepository extends EntityRepository
{

    /**
     * Filter roles
     * 
     * @access public
     * @param array $excludedRoles ,default is empty array
     * @return array roles array
     */
    public function getUsers()
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("r");

        $queryBuilder->select("r")
                ->from("Users\Entity\User", "r");
        $users = $queryBuilder->getQuery()->getResult();
        return $users;
    }

    /**
     * Filter organizations
     * 
     * @param array $userIds ,default is empty array
     * @param array $types ,default is empty array
     * @param int $status ,default is false
     * @param array $ids ,default is empty array
     * @return array organizations
     */
    public function getOrganizationsBy($userIds = array(), $types = array(), $status = false, $ids = array())
    {
        $parameters = array();
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("o");

        $queryBuilder->select("o")
                ->from("Organizations\Entity\Organization", "o");
        if (count($userIds) > 0) {
            $parameters['users'] = $userIds;
            $queryBuilder->join("o.organizationUser", "ou");
            $queryBuilder->join("ou.user", "u");
            $queryBuilder->andWhere($queryBuilder->expr()->in('u.id', ":users"));
        }
        if (count($types) > 0) {
            $parameters['types'] = $types;
            $queryBuilder->andWhere($queryBuilder->expr()->in('o.type', ":types"));
        }
        if (count($ids) > 0) {
            $parameters['ids'] = $ids;
            $queryBuilder->andWhere($queryBuilder->expr()->in('o.id', ":ids"));
        }
        if ($status !== false) {
            $parameters['status'] = $status;
            $queryBuilder->andWhere($queryBuilder->expr()->eq('o.active', ":status"));
        }
        $organizations = $queryBuilder->getQuery()->setParameters($parameters)->getResult();
        return $organizations;
    }

    public function listOrganizations($query, $type)
    {
        $em = $query->entityManager;
        $dqlQuery = $em->createQuery('SELECT u FROM Organizations\Entity\Organization u WHERE u.active = 2 and (u.type =?1 or u.type = 3)');
        $dqlQuery->setParameter(1, $type);
        return $dqlQuery->getResult();
    }

}
