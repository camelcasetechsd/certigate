<?php

namespace Utilities\Service\Paginator;

/**
 * PaginatorTrait
 * 
 * Provide paginator needed methods
 * 
 * @property Zend\Paginator\Paginator $paginator ,default is null
 * @property float $numberPerPage ,default is 10.0
 * 
 * @package utilities
 * @subpackage paginator
 */
trait PaginatorTrait
{

    /**
     *
     * @var Zend\Paginator\Paginator 
     */
    protected $paginator = NULL;

    /**
     *
     * @var int 
     */
    protected $numberPerPage = 10.0;

    /**
     * Set current page
     * @param int $currentPage
     */
    public function setPage($currentPage)
    {
        $this->paginator->setCurrentPageNumber($currentPage);
    }

    /**
     * Set number of pages
     * @param int $numberPerPage
     */
    public function setNumberPerPage($numberPerPage)
    {
        $this->numberPerPage = $numberPerPage;
    }

    /**
     * Set number of items per page
     * @param int $itemsCountPerPage
     */
    public function setItemCountPerPage($itemsCountPerPage)
    {
        $this->paginator->setItemCountPerPage($itemsCountPerPage);
    }

    /**
     * Get number of pages
     * @return int number of pages
     */
    public function getNumberOfPages()
    {
        return (int) $this->paginator->count();
    }

    /**
     * Get pages range
     * 
     * @access public
     * 
     * @param int $currentPageNumber ,default is null
     * @return array pages range
     */
    public function getPagesRange($currentPageNumber = null)
    {
        $numberOfPages = $this->getNumberOfPages();
        $pageNumbers = array();
        //create an array of page numbers
        if ($numberOfPages > 1) {
            $pageNumbers = range(1, $numberOfPages);
            foreach ($pageNumbers as $pageKey => &$pageNumber) {
                $pageNumber = array(
                    "pageNumber" => $pageKey + 1,
                    "isCurrent" => false
                );
            }
            if (empty($currentPageNumber)) {
                $currentPageNumber = 1;
            }
            $pageNumbers[$currentPageNumber - 1]["isCurrent"] = true;
        }
        return $pageNumbers;
    }

    /**
     * Get next page number
     * 
     * @access public
     * 
     * @param int $currentPageNumber ,default is null
     * @return int next page number
     */
    public function getNextPageNumber($currentPageNumber = null)
    {
        $nextPageNumber = 0;
        $numberOfPages = $this->getNumberOfPages();
        if ($numberOfPages > 1) {
            if (!empty($currentPageNumber)) {
                if ($currentPageNumber != $numberOfPages) {
                    $nextPageNumber += $currentPageNumber + 1;
                }
            } else {
                $nextPageNumber = 2;
            }
        }
        return $nextPageNumber;
    }

    /**
     * Get previous page number
     * 
     * @access public
     * 
     * @param int $currentPageNumber ,default is null
     * @return int previous page number
     */
    public function getPreviousPageNumber($currentPageNumber = null)
    {
        $previousPageNumber = 0;
        $numberOfPages = $this->getNumberOfPages();
        if ($numberOfPages > 1 && !empty($currentPageNumber)) {
            $previousPageNumber += $currentPageNumber - 1;
        }
        return $previousPageNumber;
    }

    /**
     * Get number of items per page
     * @return int number of items per page
     */
    public function getCurrentItems()
    {
        return $this->paginator;
    }

    /**
     * Set criteria
     * 
     * @access public
     * 
     * @param Doctrine\Common\Collections\Criteria $criteria
     */
    public function setCriteria($criteria)
    {
        $this->paginator->getAdapter()->setCriteria($criteria);
    }

}
