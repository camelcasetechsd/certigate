<?php

namespace Users\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * User Repository
 * 
 * @package users
 * @subpackage entity
 */
class UserRepository extends EntityRepository
{

    /**
     * Filter users
     * 
     * @access public
     * @param array $roles ,default is empty array
     * @return array users array
     */
    public function getUsers($roles = array())
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("u");
        $parameters = array();

        $queryBuilder->select("u")
                ->from("Users\Entity\User", "u");
        if (count($roles) > 0) {
            $parameters['roles'] = $roles;
            $queryBuilder->join('u.roles', 'r')
                    ->andWhere($queryBuilder->expr()->in('r.name', ":roles"));
        }
        $queryBuilder->setParameters($parameters);
        $users = $queryBuilder->getQuery()->getResult();
        return $users;
    }

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
