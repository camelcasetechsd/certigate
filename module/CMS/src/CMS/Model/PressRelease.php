<?php

namespace CMS\Model;

use Utilities\Service\Query\Query;
use Utilities\Service\Paginator\PaginatorAdapter;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections\Criteria;
use CMS\Service\PageTypes;
use Utilities\Service\Status;

/**
 * PressRelease Model
 * 
 * Handles PressRelease Entity related business
 * 
 * @property Query $query
 * 
 * @package cms
 * @subpackage model
 */
class PressRelease
{
    use \Utilities\Service\Paginator\PaginatorTrait;
    
    /**
     *
     * @var Query 
     */
    protected $query;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Query $query ,default is null
     */
    public function __construct($query = null)
    {
        $this->query = $query;
        $this->paginator = new Paginator(new PaginatorAdapter($query, "CMS\Entity\Page"));
    }

    /**
     * Filter press releases
     * 
     * @access public
     */
    public function filterPressReleases()
    {
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        $criteria->andWhere($expr->eq("type", PageTypes::PRESS_RELEASE_TYPE));
        $criteria->andWhere($expr->eq("status", Status::STATUS_ACTIVE));
        $this->setCriteria($criteria);
    }

}
