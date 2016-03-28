<?php

namespace IssueTracker\Model;

use IssueTracker\Service\IssueCategories;
use IssueTracker\Entity\IssueCategory as CategoryEntity;
use Doctrine\Common\Collections\Criteria;

class Categories
{
    /*
     *
     * @var Utilities\Service\Query\Query 
     */

    protected $query;

    /**
     *
     * @var Notifications\Service\Notification
     */
    protected $notification;

    /**
     * Set needed properties
     * 
     * 
     * @access public
     * 
     * @param Utilities\Service\Query\Query $query
     * @param Notifications\Service\Notification $notification
     */
    public function __construct($query, $notification)
    {
        $this->query = $query;
        $this->notification = $notification;
    }

    /**
     * function to check if a category has children or Not
     * @param type $category
     * @return boolean
     */
    public function hasChildren($category)
    {
        $children = $this->query->findBy('IssueTracker\Entity\IssueCategory', array(
            'parent' => $category->getId()
        ));
        if (!empty($children)) {
            return true;
        }
        return false;
    }

    /**
     * function perpare categories to be listed
     * @param Array $categories
     * @return Array | IssueTracker/Entity/IssueCategory 
     */
    public function prepareCategoriesDisplay($categories)
    {
        foreach ($categories as $category) {
            $category->hasChildren = $this->hasChildren($category);
            $category->getParent() == null ? $category->parent = '#' : $category->parent = $category->getParent()->getTitle();
        }
        return $categories;
    }

    /**
     *  list categories ( | except for default categories) 
     * @param boolean $defaultFlag  if true  execlude default category
     * @return Array | IssueTracker/Entity/IssueCategory
     */
    public function getCategories($defaultFlag = false)
    {
        if ($defaultFlag) {
            $criteria = Criteria::create();
            $expr = Criteria::expr();
            $criteria->andWhere($expr->neq('title', IssueCategories::DEFAULT_CATEGORY_TEXT));
            $categories = $this->query->filter(/* $entityName = */'IssueTracker\Entity\IssueCategory', $criteria);
        }
        else {
            $categories = $this->query->findAll('IssueTracker\Entity\IssueCategory');
        }
        return $categories;
    }

    /**
     * assigning children to another parent categories
     * 
     * @param type $oldParentId
     * @param type $newParentId
     */
    public function assignChildrenTo($oldParentId, $newParentId = null)
    {
        if (is_null($newParentId)) {
            $newParentObj = $this->query->findOneBy('IssueTracker\Entity\IssueCategory', array(
                'title' => IssueCategories::DEFAULT_CATEGORY_TEXT
            ));
        }
        else {
            $newParentObj = $this->query->findOneBy('IssueTracker\Entity\IssueCategory', array(
                'id' => $newParentId
            ));
        }
        $children = $this->query->findBy('IssueTracker\Entity\IssueCategory', array(
            'parent' => $oldParentId
        ));
        // only if deleted parent has children
        if ($children != null) {
            foreach ($children as $child) {
                $child->setParent($newParentObj);
                $this->query->save($child);
            }
        }
    }

    /**
     * Remove category
     * @param type $oldParentId
     * @param type $newParentId
     * 
     * @return Boolean
     */
    public function removeCategory($oldParentId, $newParentId = null)
    {
        $oldcategory = $this->query->findOneBy('IssueTracker\Entity\IssueCategory', array(
            'id' => $oldParentId
        ));
        // if trying to delete default category
        if ($oldcategory->getTitle() == IssueCategories::DEFAULT_CATEGORY_TEXT) {
            return false;
        }
        $this->assignChildrenTo($oldParentId, $newParentId);
        $this->query->remove($oldcategory);
    }

    /**
     * Saving category in both new and edit
     * @param type $data
     * @param type $categoryObj
     */
    public function saveCategory($data, $categoryObj = null)
    {
        if (is_null($categoryObj)) {
            $categoryObj = new CategoryEntity();
        }
        //setting patent Category
        if (empty($data['parent'])) {
            $categoryObj->setParent(null);
            //setting category depth
            $categoryObj->setDepth(1);
        }
        else {
            $parent = $this->query->findOneBy('IssueTracker\Entity\IssueCategory', array(
                'id' => $data['parent']
            ));
            $categoryObj->setParent($parent);
            //setting category depth
            $categoryObj->setDepth($parent->getDepth() + 1);
        }
        // saving category object
        $this->query->setEntity('IssueTracker\Entity\IssueCategory')->save($categoryObj, $data);
    }

}
