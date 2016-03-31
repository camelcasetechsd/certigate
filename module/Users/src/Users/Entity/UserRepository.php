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
     * @param bool $status ,default is null
     * @param int $offset ,default is null
     * @param int $limit ,default is null
     * @param bool $countFlag ,default is false
     * @return array users array
     */
    public function getUsers($roles = array(), $status = null, $offset = null, $limit = null, $countFlag = false)
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("u");
        $parameters = array();

        if ($countFlag === false) {
            $select = "u";
        }
        else {
            $select = $queryBuilder->expr()->count('u');
        }
        $queryBuilder->select($select)
                ->from("Users\Entity\User", "u");
        if (count($roles) > 0) {
            $parameters['roles'] = $roles;
            $queryBuilder->join('u.roles', 'r')
                    ->andWhere($queryBuilder->expr()->in('r.name', ":roles"));
        }
        if (!is_null($status)) {
            $parameters['status'] = $status;
            $queryBuilder->andWhere($queryBuilder->expr()->eq('u.status', ":status"));
        }
        if ($countFlag === false) {
            if (is_numeric($offset)) {
                $queryBuilder->setFirstResult($offset);
            }
            if (is_numeric($limit)) {
                $queryBuilder->setMaxResults($limit);
            }
        }
        $queryBuilder->setParameters($parameters);
        $query = $queryBuilder->getQuery();
        if ($countFlag === false) {
            $result = $query->getResult();
        }
        else {
            $result = $query->getSingleScalarResult();
        }
        return $result;
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
