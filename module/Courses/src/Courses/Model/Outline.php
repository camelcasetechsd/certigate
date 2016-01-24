<?php

namespace Courses\Model;

use Doctrine\Common\Collections\Criteria;

/**
 * Outline Model
 * 
 * Handles Outline Entity related business
 * 
 * 
 * @property Utilities\Service\Query\Query $query
 * 
 * @package courses
 * @subpackage model
 */
class Outline
{

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Remove not used outlines
     * 
     * @access public
     */
    public function cleanUpOutlines()
    {
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        $criteria->andWhere($expr->isNull("course"));
        $outlinesToBeRemoved = $this->query->filter("Courses\Entity\Outline", $criteria);
        foreach ($outlinesToBeRemoved as $outline) {
            $this->query->remove($outline);
        }
    }

}
