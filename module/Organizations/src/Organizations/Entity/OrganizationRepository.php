<?php

namespace Organizations\Entity;

use Doctrine\ORM\EntityRepository;
use Utilities\Service\Status;
use Organizations\Entity\Organization;

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
            $queryBuilder->andWhere($queryBuilder->expr()->eq('o.status', ":status"));
        }
        $organizations = $queryBuilder->getQuery()->setParameters($parameters)->getResult();
        return $organizations;
    }

    /**
     * List active organizations by type
     * 
     * @access public
     * @param int $type
     * @return array active organizations list
     */
    public function listOrganizations($type)
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("o");

        $parameters = array(
            'status' => Status::STATUS_ACTIVE,
//            'types' => array(
//                $type,
//                Organization::TYPE_BOTH
//            )
        );
        
        $queryBuilder->select("o")
                ->from("Organizations\Entity\Organization", "o")
                ->andWhere($queryBuilder->expr()->eq('o.status', ":status"))
//                ->andWhere($queryBuilder->expr()->in('o.type', ":types"));
        ;
        $organizations = $queryBuilder->getQuery()->setParameters($parameters)->getResult();
        return $organizations;
    }

}
