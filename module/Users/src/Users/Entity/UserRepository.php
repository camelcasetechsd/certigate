<?php

namespace Users\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Role Repository
 * 
 * @package users
 * @subpackage entity
 */
class UserRepository extends EntityRepository
{

    /**
     * list all users
     * 
     * @access public
     * @param array $excludedRoles ,default is empty array
     * @return array users array
     */
    public function listUsers()
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("r");

        $queryBuilder->select("r")
                ->from("Users\Entity\User", "r");
        $users = $queryBuilder->getQuery()->getResult();
        return $users;
    }

}
