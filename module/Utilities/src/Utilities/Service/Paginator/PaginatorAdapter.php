<?php

namespace Utilities\Service\Paginator;

use Zend\Paginator\Adapter\AdapterInterface;
use Doctrine\Common\Collections\Criteria;

/**
 * Paginator Adapter
 * 
 * Handles pagination related business
 * 
 * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
 * 
 * @property Doctrine\Common\Collections\Criteria $criteria
 * @property Utilities\Service\Query\Query $query
 * @property string $entityName
 * 
 * @package utilities
 * @subpackage query
 */
class PaginatorAdapter implements AdapterInterface
{

    /**
     *
     * @var Doctrine\Common\Collections\Criteria
     */
    protected $criteria;

    /**
     *
     * @var Utilities\Service\Query\Query 
     */
    protected $query;

    /**
     *
     * @var string
     */
    protected $entityName;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @throws \Exception
     */
    public function __construct($query, $entityName)
    {
        $this->query = $query;
        $this->entityName = $entityName;
    }

    /**
     * Count items
     * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
     * 
     * @access public
     * @param string $mode ,default is COUNT_NORMAL
     * @return int items count
     */
    public function count($mode = 'COUNT_NORMAL')
    {
        $this->setCriteria();
        return $this->query->filter($this->entityName, $this->criteria, /* $countFlag = */ true);
    }

    /**
     * Get items
     * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
     * 
     * @access public
     * 
     * @param int $offset
     * @param int $itemCountPerPage
     * @return array items queried
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $this->setCriteria();
        $this->criteria->setFirstResult($offset);
        $this->criteria->setMaxResults($itemCountPerPage);
        return $this->query->filter($this->entityName, $this->criteria); //($pageNumber-1) for zero based count
    }

    /**
     * Set criteria
     * @access public
     * 
     * @param Doctrine\Common\Collections\Criteria $criteria ,default is null
     * @return \Utilities\Service\Paginator\PaginatorAdapter
     */
    public function setCriteria($criteria = null)
    {

        if (is_null($criteria) && !is_object($this->criteria)) {
            $criteria = Criteria::create();
        }
        if (!is_null($criteria)) {
            $this->criteria = $criteria;
        }
        return $this;
    }

}
