<?php

namespace Courses\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Vote Repository
 * 
 * @package courses
 * @subpackage entity
 */
class VoteRepository extends EntityRepository
{

    /**
     * Get votes for course event
     * 
     * @access public
     * @param int $courseEventId
     * @return array
     */
    public function getVotesByCourseEvent($courseEventId)
    {
        $parameters = array();
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("v");

        $queryBuilder->select("v.vote, q.id, q.questionTitle, q.questionTitleAr")
                ->from("Courses\Entity\Vote", "v")
                ->join("v.courseEvent", "ce", Join::WITH, $queryBuilder->expr()->eq( 'v.courseEvent', 'ce.id' ))
                ->join("v.question", "q", Join::WITH, $queryBuilder->expr()->eq( 'v.question', 'q.id' ));

        $parameters['courseEventId'] = $courseEventId;
        $queryBuilder->andWhere($queryBuilder->expr()->lte('ce.id', ":courseEventId"));

        return $queryBuilder->setParameters($parameters)->getQuery()->getResult();
    }

}
