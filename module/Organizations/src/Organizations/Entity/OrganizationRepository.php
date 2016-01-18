<?php

namespace Organizations\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Role Repository
 * 
 * @package users
 * @subpackage entity
 */
class OrgRepository extends EntityRepository
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
}
