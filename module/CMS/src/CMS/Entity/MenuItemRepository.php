<?php

namespace CMS\Entity;

use Doctrine\ORM\EntityRepository;
use CMS\Model\MenuItem as MenuItemModel;

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
     * @return array
     */
    public function getMenuItemsSorted($hiddenMenuItemsIds = array()) {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder('mt');
        $queryBuilder->select('mt')
                ->from("CMS\Entity\MenuItem", "mt")
                ->orderBy('mt.menu,mt.weight', 'ASC');
        if(count($hiddenMenuItemsIds) > 0){
            $parameters = array(
                'hiddenMenuItemsIds' => $hiddenMenuItemsIds
            );
            $queryBuilder->andWhere($queryBuilder->expr()->notIn('mt.id', ":hiddenMenuItemsIds"))
                ->setParameters($parameters);
        }

        $menuItems = $queryBuilder->getQuery()->getResult();

        $menuItemModel = new MenuItemModel();
        $menuItemsTree = $menuItemModel->getSortedMenuItems($menuItems);
        return $menuItemsTree;
    }

    

}
