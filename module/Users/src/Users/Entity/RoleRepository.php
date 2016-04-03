<?php

namespace Users\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Criteria;

/**
 * Role Repository
 * 
 * @package users
 * @subpackage entity
 */
class RoleRepository extends EntityRepository {

    /**
     * Filter roles
     * 
     * @access public
     * @param array $excludedRoles ,default is empty array
     * @return array roles array
     */
    public function getRoles($excludedRoles = array()) {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("r");
        $parameters = array();

        $queryBuilder->select("r")
                ->from("Users\Entity\Role", "r");
        if (count($excludedRoles) > 0) {
            $parameters['excludedRoles'] = $excludedRoles;
            $queryBuilder->andWhere($queryBuilder->expr()->notIn('r.name', ":excludedRoles"));
        }
        $queryBuilder->orderBy('r.name', Criteria::DESC)->setParameters($parameters);

        $roles = $queryBuilder->getQuery()->getResult();
        return $roles;
    }

}
