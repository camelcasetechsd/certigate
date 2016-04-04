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
class OrganizationMetaRepository extends EntityRepository
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
    public function getMyOrganizations($currentUserId, $offset = null, $limit = null, $countFlag = false)
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("om");
        $parameters = array();

        if ($countFlag === false) {
            $select = "om";
        }
        else {
            $select = $queryBuilder->expr()->count("om");
        }
        $expr = $queryBuilder->expr();
        $queryBuilder->select($select)
                ->from("Organizations\Entity\OrganizationMeta", "om");
        $queryBuilder->leftJoin('om.organization', 'o');
        $queryBuilder->leftJoin('o.organizationUser', 'ou');
        $queryBuilder->where($expr->eq('o.creatorId', "$currentUserId"))
                ->orWhere($expr->eq('ou.user', "$currentUserId"));

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

            foreach ($result as $meta) {
                $meta->type = $meta->getType()->getTitle();
                $meta->expirationDate == null ? $meta->expirationDate = 'NO Expiration Date' : $meta->expirationDate = $meta->expirationDate->format('d/m/Y');
            }
        }
        else {
            $result = $query->getSingleScalarResult();
        }
        return $result;
    }

}
