<?php

namespace Courses\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CourseEventSubscription Repository
 * 
 * @package courses
 * @subpackage entity
 */
class CourseEventSubscriptionRepository extends EntityRepository
{

    /**
     * Get course event subscriptions
     * 
     * @access public
     * @param int $lastNotifiedDays ,default is null
     * @return array
     */
    public function getCourseEventSubscriptions($lastNotifiedDays = null)
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("ces");

        $queryBuilder->select("ces")
                ->from("Courses\Entity\CourseEventSubscription", "ces")
                ->join("ces.courseEvent", "ce");

        $parameters = array();
        if (is_numeric($lastNotifiedDays)) {
            $lastNotifiedDate = new \DateTime("- $lastNotifiedDays days");
            $parameters['lastNotifiedDays'] = $lastNotifiedDate;
            $queryBuilder->andWhere($queryBuilder->expr()->lte('ces.lastNotified', ":lastNotifiedDays"));
        }
        $today = new \DateTime();
        $parameters['today'] = $today;
        $queryBuilder->andWhere($queryBuilder->expr()->gte('ce.endDate', ":today"));

        return $queryBuilder->setParameters($parameters)->getQuery()->getResult();
    }

}
