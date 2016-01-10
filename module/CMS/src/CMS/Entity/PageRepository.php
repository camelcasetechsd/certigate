<?php

namespace CMS\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Page Repository
 * 
 * @package cms
 * @subpackage entity
 */
class PageRepository extends EntityRepository {

    /**
     * Get page by path
     * 
     * @access public
     * @param string $path
     * @return Page
     */
    public function getPageByPath($path) {
        $repository = $this->getEntityManager();
        $queryBuilder = $repository->createQueryBuilder("p");
        $parameters = array("path" => $path);

        $queryBuilder->select("p")
                ->from("CMS\Entity\Page", "p")
                ->innerJoin('p.menuItem', 'mt')
                ->orderBy('mt.menu,mt.weight', 'ASC')
                ->andWhere($queryBuilder->expr()->eq('mt.path', ":path"))
                ->setParameters($parameters);

        $pages = $queryBuilder->getQuery()->getResult();
        return reset($pages);
    }

}
