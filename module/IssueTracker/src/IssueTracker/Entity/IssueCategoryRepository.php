<?php

namespace IssueTracker\Entity;

use Doctrine\ORM\EntityRepository;
use IssueTracker\Service\IssueCategories;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IssueCategoryRepository
 *
 * @author ahmedreda
 */
class IssueCategoryRepository extends EntityRepository
{

    /**
     * Sort categories with their children to depth 3  except for default category
     * 
     * @return array of categories sorted
     */
    public function getCategoriesSorted()
    {
        // getting all categories excluding default category
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder();
        $expr = $queryBuilder->expr();
        $queryBuilder->select('i')
                ->from('IssueTracker\Entity\IssueCategory', 'i')
                ->andWhere($expr->andX(
                                $expr->isNull('i.parent'), $expr->neq('i.title', '?1')
                        )
                )->setParameter('1', IssueCategories::DEFAULT_CATEGORY_TEXT);

        $parents = $queryBuilder->getQuery()->getResult();
        // preparing categories for show
        return $this->prepareCategories($parents);
    }

    /**
     * Prepare Categories sorted with depth meter separtor
     * 
     * @param type $parents
     * @return type
     */
    private function prepareCategories($parents)
    {
        $categoryList = array();
        foreach ($parents as $parent) {
            array_push($categoryList, $parent);
            $children = $this->getChildren($parent);
            if ($children != null) {
                foreach ($children as $child) {
                    array_push($categoryList, $child);
                    $childChildren = $this->getChildren($child);
                    if ($childChildren != null) {
                        foreach ($childChildren as $cC) {
                            array_push($categoryList, $cC);
                        }
                    }
                }
            }
        }
        // adding depth meter separators for each category
        return $this->prepareDepthView($categoryList);
    }

    /**
     * Getting children categories for specific parent catrgory
     * 
     * @param type $parent
     * @return type
     */
    private function getChildren($parent)
    {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder();
        $expr = $queryBuilder->expr();

        $queryBuilder->select('i')
                ->from('IssueTracker\Entity\IssueCategory', 'i')
                ->andWhere($expr->eq('i.parent', $parent->getId()
                        )
                )->orderBy('i.weight', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Adding depth meter separetor for each category as category real tree
     * @param type $list
     * @return type
     */
    private function prepareDepthView($list)
    {
        foreach ($list as $category) {
            $category->setTitle(str_repeat(IssueCategories::DEPTH_METER_SEPARATOR, $category->getDepth()) . $category->getTitle());
        }
        return $list;
    }

}
