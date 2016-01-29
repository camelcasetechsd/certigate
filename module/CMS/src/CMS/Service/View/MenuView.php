<?php

namespace CMS\Service\View;

use CMS\Entity\Menu;

/**
 * MenuView
 * 
 * Handles CMS menu view related business
 * 
 * 
 * @property string $menuCloseString
 * @property string $menuOpenString
 * @property string $subMenuCloseString
 * @property string $subMenuOpenString
 * @property string $menuItemOpenString
 * @property string $menuItemCloseString
 * @property string $menuItemLiAttributesString
 * @property string $menuItemAnchorAttributesString
 * @property array $primaryMenuAttributes
 * 
 * @package cms
 * @subpackage view
 */
class MenuView {

    /**
     * Menu div class
     */
    const DIV_CLASS = "divClass";

    /**
     * Menu ul class
     */
    const UL_CLASS = "ulClass";

    /**
     *
     * @var string 
     */
    protected $menuCloseString = '</ul></div>';

    /**
     *
     * @var string 
     */
    protected $menuOpenString = '<div class="%s"><ul class="%s">';

    /**
     *
     * @var string 
     */
    protected $subMenuCloseString = '</ul>';

    /**
     *
     * @var string 
     */
    protected $subMenuOpenString = '<ul class="%s">';

    /**
     *
     * @var string 
     */
    protected $menuItemOpenString = '<li %s><a %s href="%s">%s%s</a>';
    
    /**
     *
     * @var string 
     */
    protected $menuItemCloseString = '</li>';

    /**
     *
     * @var string 
     */
    protected $menuItemLiAttributesString = 'class="menu-item-li %1$s menu-li-%2$s menu-%2$s"';

    /**
     *
     * @var string 
     */
    protected $menuItemAnchorAttributesString = 'class="%1$s menu-anchor-%2$s menu-%2$s"';

    /**
     *
     * @var string 
     */
    protected $childIndicator = '<span class="fa arrow"></span>';

    /**
     *
     * @var array 
     */
    protected $primaryMenuAttributes = array(
        self::DIV_CLASS => "primarymenu navbar-collapse",
        self::UL_CLASS => "nav",
    );

    /**
     *
     * @var array 
     */
    protected $subMenuAttributes = array(
        self::UL_CLASS => "nav",
    );

    /**
     * Prepare menu for view by it's title
     * 
     * 
     * @access public
     * @param array $menusArray
     * @param string $menuTitleUnderscored menu title underscored ,default is null
     * @param string $divClass ,default is empty string
     * @param string $ulClass ,default is empty string
     * @return array menu HTML view for menu title underscored as the key
     */
    public function prepareMenuView($menusArray, $menuTitleUnderscored = null, $divClass = '', $ulClass = '', $depthLevel = 0) {
        // Menu open
        if (!is_null($menuTitleUnderscored) && array_key_exists($menuTitleUnderscored, $menusArray)) {
            $menusArray = array($menuTitleUnderscored => $menusArray[$menuTitleUnderscored]);
            if ($menuTitleUnderscored === Menu::PRIMARY_MENU_UNDERSCORED) {
                $divClass = $this->primaryMenuAttributes[self::DIV_CLASS];
                $ulClass = $this->primaryMenuAttributes[self::UL_CLASS];
            }
        // Sub menu
        } else {
            $ulClass = $this->subMenuAttributes[self::UL_CLASS];
        }

        $menuViewArray = array();

        foreach ($menusArray as $menuTitleUnderscored => $menuItemsArray) {
            if ($depthLevel === 0 && $menuItemsArray == reset($menusArray)) {
                $menuView = sprintf($this->menuOpenString, $divClass, $ulClass);
            } elseif($depthLevel !== 0) {
                $menuView = sprintf($this->subMenuOpenString, $ulClass);
            }
            foreach ($menuItemsArray as $menuItemTitle => $menuItemArray) {
                $depthLevel = $menuItemArray['depth'];
                $menuItemTitleUnderscored = $menuItemArray['title_underscored'];
                $liAttributes = sprintf($this->menuItemLiAttributesString, $menuItemTitleUnderscored, $depthLevel);
                $anchorAttributes = sprintf($this->menuItemAnchorAttributesString, $menuItemTitleUnderscored, $depthLevel);
                $condChildIndicator = '';
                if (count($menuItemArray["children"]) > 0) {
                    $condChildIndicator = $this->childIndicator;
                }
                $menuView .= sprintf($this->menuItemOpenString, $liAttributes, $anchorAttributes, $menuItemArray['path'], $menuItemTitle, $condChildIndicator);
                if (count($menuItemArray["children"]) > 0) {
                    $menuView .= implode(" ", $this->prepareMenuView($menuItemArray["children"], /* $menuTitleUnderscored = */ null, /* $divClass = */ '', /* $ulClass = */ '', $depthLevel + 1));
                }
                $menuView .= $this->menuItemCloseString;
            }
            
            if ($depthLevel === 0 && $menuItemsArray == end($menusArray)) {
                $menuView .= $this->menuCloseString;
            } elseif($depthLevel !== 0) {
                $menuView .= $this->subMenuCloseString;
            }
            $menuViewArray[$menuTitleUnderscored] = $menuView;
        }

        return $menuViewArray;
    }

}
