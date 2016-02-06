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
 * @property Utilities\Service\Query\Query $query
 * 
 * @package utilities
 * @subpackage query
 */
class PaginatorAdapter implements AdapterInterface
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
     * @throws \Exception
     */
    public function __construct( $query)
    {
//        if(empty($query->entityName)){
//            throw new \Exception('query entityName property should be set');
//        }
        $this->query = $query;
    }
    
    /**
     * Count items
     * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
     * 
     * @access public
     * @param string $mode ,default is COUNT_NORMAL
     * @return int items count
     */
    public function count($mode = 'COUNT_NORMAL') {
        return $this->query->filter(/*$entityName =*/ null, /*$criteria =*/ false, /*$countFlag =*/ true);
    }

    /**
     * Get items
     * @author Mohamed Labib <mohamed.labib@camelcasetech.com>
     * 
     * @access public
     * @uses Criteria
     * 
     * @param int $offset
     * @param int $itemCountPerPage
     * @return array items queried
     */
    public function getItems($offset, $itemCountPerPage) {
        $criteria = new Criteria(/*$expression =*/ null, /*$orderings =*/ null, /*$firstResult =*/ $offset, /*$maxResults =*/ $itemCountPerPage);
        return  $this->query->filter(/*$entityName =*/ null, $criteria); //($pageNumber-1) for zero based count
    }

}