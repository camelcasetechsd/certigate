<?php

require_once __DIR__ . '/../AbstractMigration.php';

use db\AbstractMigration;
use \CMS\Entity\MenuItem;

class ExamsMenu extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $adminMenuId = $this->fetchRow('SELECT id FROM menu WHERE title="Admin Menu"')['id'];

        $examsMenuItems = array(
            "Exams" => array(
                'depth' => 0,
                'path' => "#",
                'weight' => 3,
                'title' => "Exams",
                'titleAr' => "Exams",
                'children' => array(
                    "Book Exam" => array(
                        'depth' => 1,
                        'path' => "/courses/exam/book",
                        'weight' => 0,
                        'title' => "Book Exam",
                        'titleAr' => "Book Exam",
                    ),
                    "Exam Requests" => array(
                        'depth' => 1,
                        'path' => "/courses/exam/requests",
                        'weight' => 1,
                        'title' => "Exam Requests",
                        'titleAr' => "Exam Requests",
                    )
                )
            )
        );

        foreach ($examsMenuItems as $item) {
            $this->insertMenuItem($item, $adminMenuId);
        }
    }

    public function insertMenuItem($item, $primaryMenuId, $parentId = null)
    {
        $menuItem = [];
        $menuItem['directUrl'] = $item['path'];
        $menuItem['type'] = MenuItem::TYPE_DIRECT_URL;

        $menuItem['parent_id'] = $parentId;
        $menuItem['menu_id'] = $primaryMenuId;
        $menuItem['title'] = $item['title'];
        $menuItem['titleAr'] = $item['titleAr'] . ' ar';
        $menuItem['weight'] = (isset($item['weight'])) ? $item['weight'] : 1;
        $menuItem['status'] = true;
        $menuItem['created'] = date('Y-m-d H:i:s');
                
        $this->insert('menuItem', $menuItem);

        $menuItemParentId = $this->getAdapter()->getConnection()->lastInsertId();
        if (isset($item['children']) && count($item['children']) > 0) {
            foreach ($item['children'] as $childItem) {
                $this->insertMenuItem($childItem, $primaryMenuId, $menuItemParentId);
            }
        }
    }
}
