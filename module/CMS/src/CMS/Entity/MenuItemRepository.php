<?php

namespace CMS\Entity;

use Doctrine\ORM\EntityRepository;
use CMS\Model\MenuItem as MenuItemModel;
use Doctrine\ORM\Query\Expr\Join;

/**
 * MenuItem Repository
 * 
 * @package cms
 * @subpackage entity
 */
class MenuItemRepository extends EntityRepository {

    /**
     * Get menu items sorted by menu and menu items' parents
     * 
     * @access public
     * @param array $hiddenMenuItemsIds ,default is empty array
     * @param bool $menuItemStatus ,default is null
     * @param bool $menuStatus ,default is null
     * @param bool $treeFlag ,default is false
     * @return array
     */
    public function getMenuItemsSorted($hiddenMenuItemsIds = array(), $menuItemStatus = null, $menuStatus = null, $treeFlag = false) {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder('mt');
        $parameters = array();
        $queryBuilder->select('mt')
                ->from("CMS\Entity\MenuItem", "mt")
                ->orderBy('mt.menu,mt.weight', 'ASC');
        if(count($hiddenMenuItemsIds) > 0){
            $parameters['hiddenMenuItemsIds'] = $hiddenMenuItemsIds;
            $queryBuilder->andWhere($queryBuilder->expr()->eq('mt.id', ":hiddenMenuItemsIds"));
        }
        if( !is_null($menuItemStatus) ){
            $parameters['menuItemStatus'] = $menuItemStatus;
            $queryBuilder->andWhere($queryBuilder->expr()->eq('mt.status', ":menuItemStatus"));
        } 
        if( !is_null($menuStatus) ){
            $parameters['menuStatus'] = $menuStatus;
            $queryBuilder->innerJoin('CMS\Entity\Menu', 'm', Join::WITH, $queryBuilder->expr()->eq('mt.menu', 'm.id'))
                    ->andWhere($queryBuilder->expr()->eq('m.status', ":menuStatus"));
        } 
        if(count($parameters) > 0){
            $queryBuilder->setParameters($parameters);
        }
        $menuItems = $queryBuilder->getQuery()->getResult();

        $menuItemModel = new MenuItemModel();
        $menuItemsTree = $menuItemModel->getSortedMenuItems($menuItems, /*$root =*/ 0, $treeFlag);
        return $menuItemsTree;
    }

    

}
