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
 * @property string $methodName
 * @property array $parameters
 * 
 * @package utilities
 * @subpackage paginator
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
     *
     * @var string
     */
    protected $methodName;

    /**
     *
     * @var array
     */
    protected $parameters;

    /**
     * Set needed properties
     * 
     * @access public
     * @param Utilities\Service\Query\Query $query
     * @param string $entityName
     * @param string $methodName ,default is 'filter'
     * @param array $parameters ,default is empty array
     * @throws \Exception
     */
    public function __construct($query, $entityName, $methodName = "filter", $parameters = array())
    {
        $this->query = $query;
        $this->entityName = $entityName;
        $this->setMethodName($methodName);
        $this->setParameters($parameters);
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
        return $this->filter(/* $objectRepository = */ $this->query, /* $methodName = */ $this->methodName, /* $parameters = */ $this->parameters, /* $countFlag = */ true);
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
        return $this->filter(/* $objectRepository = */ $this->query, /* $methodName = */ $this->methodName, /* $parameters = */ $this->parameters, /* $countFlag = */ false, $offset, $itemCountPerPage); //($pageNumber-1) for zero based count
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

    /**
     * Set method name
     * @access public
     * 
     * @param string $methodName
     * @return \Utilities\Service\Paginator\PaginatorAdapter
     */
    public function setMethodName($methodName = "filter")
    {
        $this->methodName = $methodName;
        return $this;
    }

    /**
     * Set parameters
     * @access public
     * 
     * @param array $parameters
     * @return \Utilities\Service\Paginator\PaginatorAdapter
     */
    public function setParameters($parameters = array())
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Set query
     * @access public
     * 
     * @param object $query
     * @return \Utilities\Service\Paginator\PaginatorAdapter
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Filter paginator items
     * @access public
     * 
     * @param object $objectRepository
     * @param string $methodName
     * @param bool $countFlag
     * @param int $offset
     * @param int $itemCountPerPage
     * @return array filtered items
     */
    public function filter($objectRepository, $methodName = 'filter', $parameters = array(), $countFlag = false, $offset = false, $itemCountPerPage = false)
    {
        if (empty($parameters)) {
            if ($countFlag === false && is_numeric($offset) && is_numeric($itemCountPerPage)) {
                $this->criteria->setFirstResult($offset)->setMaxResults($itemCountPerPage);
            }
            $parameters["entityName"] = $this->entityName;
            $parameters["criteria"] = $this->criteria;
        }else{
            $parameters["offset"] = $offset;
            $parameters["limit"] = $itemCountPerPage;
        }
        $parameters["countFlag"] = $countFlag;
        return call_user_func_array(array($objectRepository, $methodName), $parameters);
    }

}
