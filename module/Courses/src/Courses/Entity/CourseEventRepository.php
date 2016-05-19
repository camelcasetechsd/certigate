<?php

namespace Courses\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CourseEvent Repository
 * 
 * @package courses
 * @subpackage entity
 */
class CourseEventRepository extends EntityRepository
{

    /**
     * Get course events by criteria like user id
     * 
     * @access public
     * @param int $userId ,default is false
     * @return array
     */
    public function getCourseEventsBy($userId = false)
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("ce");

        $queryBuilder->select("ce")
                ->from("Courses\Entity\CourseEvent", "ce")
                ->join("ce.courseEventUsers", "ceu");

        $parameters = array();
        if (is_numeric($userId)) {
            $parameters['userId'] = $userId;
            $queryBuilder->andWhere($queryBuilder->expr()->eq('ceu.user', ":userId"));
        }
        return $queryBuilder->setParameters($parameters)->getQuery()->getResult();
    }

}
