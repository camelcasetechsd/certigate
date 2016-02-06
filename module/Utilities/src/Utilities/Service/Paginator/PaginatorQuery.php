<?php

namespace Utilities\Service\Paginator;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\Criteria;

/**
 * Query
 * 
 * Handles database queries related business
 * Wrapping commonly used database queries
 * 
 * 
 * 
 * @property ObjectManager $entityManager
 * @property Doctrine\Common\Persistence\ObjectRepository $entityRepository
 * @property string $entityName
 * 
 * @package utilities
 * @subpackage query
 */
class PaginatorQuery extends \Utilities\Service\Query\Query
{
    /**
     * Filter entities by a set of criteria.
     * Only count of entities can be retrieved
     * 
     * 
     * @access public
     * @uses Criteria
     * 
     * @param string $entityName
     * @param mixed $criteria Criteria instance ,default is bool false
     * @param bool $countFlag ,default is bool false
     * @return mixed array of results or just int if count is required
     */
    public function filter($entityName, $criteria = false, $countFlag = false)
    {
        if (!$criteria instanceof Criteria) {
            $criteria = new Criteria();
        }
        $return = $this->setEntity($entityName)->entityRepository->matching($criteria);
        if ($countFlag === true) {
            $return = (int) $return->count();
        }
        return $return;
    }
}
