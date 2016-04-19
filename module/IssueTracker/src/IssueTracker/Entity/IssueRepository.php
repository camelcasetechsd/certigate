<?php

namespace IssueTracker\Entity;

use Doctrine\ORM\EntityRepository;

class IssueRepository extends EntityRepository
{

    public function getIssues($issueModelObj, $offset = null, $limit = null, $countFlag = false)
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("i");
        $parameters = array();

        if ($countFlag === false) {
            $select = "i";
        }
        else {
            $select = $queryBuilder->expr()->count('i');
        }
        $queryBuilder->select($select)
                ->from("IssueTracker\Entity\Issue", "i");

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
            $data = $query->getResult();
            $result = $issueModelObj->prepareIssuesToView($data);
        }
        else {
            $result = $query->getSingleScalarResult();
        }
        return $result;
    }

}
