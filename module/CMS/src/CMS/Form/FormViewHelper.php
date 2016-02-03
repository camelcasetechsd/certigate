<?php

namespace CMS\Form;

use Zend\Form\FormInterface;
use Utilities\Form\FormViewHelper as OriginalFormViewHelper;
use DoctrineModule\Form\Element\ObjectSelect;
use CMS\Entity\MenuItem;
use Utilities\Service\Status;

/**
 * FormView Helper
 * 
 * Handles form elements display
 * 
 * 
 *
 * @package cms
 * @subpackage form
 */
class FormViewHelper extends OriginalFormViewHelper {

    /**
     * Render a form from the provided $form,
     * 
     * @access public
     * @param  FormInterface $form
     * @return string form HTML content
     */
    public function render(FormInterface $form) {
        foreach ($form as $element) {
            // handle menu item and parent fields drop down display
            if ($element instanceof ObjectSelect && $element->getName() === "menuItem" || $element->getName() === "parent") {
                $valueOptions = $element->getValueOptions();
                // get entity manager
                $entityManager = $element->getProxy()->getObjectManager();
                $valueLabels = array();
                foreach ($valueOptions as $valueKey => &$valueOption) {
                    // empty value for outermost menu level is added again with proper configuration
                    if ((empty($valueKey) && !is_numeric($valueKey))) {
                        $emptyKeyValue = $valueOption;
                        unset($valueOptions[$valueKey]);
                        continue;
                    }
                    // escape new keys added to $valueOptions array
                    if (in_array($valueKey, $valueLabels)) {
                        continue;
                    }

                    $isActive = true;
                    $valueOption["options"] = array();
                    foreach ($valueOption as $propertyName => $propertyValue) {
                        // process label to get menu data
                        if ($propertyName === "label") {
                            $propertyValueItems = explode(/* $delimiter = */ MenuItem::MENU_ITEM_TITLE_SEPARATOR, $propertyValue);
                            $menuId = reset($propertyValueItems);
                            $menuTitle = next($propertyValueItems);
                            $propertyValue = end($propertyValueItems);
                            // determine if menu item is active or not
                            if (strpos($propertyValue, Status::STATUS_INACTIVE_TEXT) !== false) {
                                $isActive = false;
                            }
                        }
                        if ($propertyName === "attributes") {
                            // add menu id to option attributes
                            $propertyValue['data-menu'] = $menuId;
                            // style inactive menu item
                            if ($isActive === false) {
                                $propertyValue['class'] = "container-inactive";
                            }
                        }
                        // add menu item data to options array while removing other keys in menu item data array
                        if ($propertyName !== "options") {
                            $valueOption["options"][0][$propertyName] = $propertyValue;
                            unset($valueOption[$propertyName]);
                        }
                    }
                    $valueOption["label"] = $menuTitle;
                    $valueLabels[] = $menuTitle;
                    
                    // append option to corresponding menu options
                    if (array_key_exists($menuTitle, $valueOptions)) {
                        $valueOptions[$menuTitle]["options"][] = $valueOption["options"][0];
                    } else {
                        $valueOptions[$menuTitle] = $valueOption;
                    }
                    unset($valueOptions[$valueKey]);
                }
                // add root options for each menu
                if (isset($emptyKeyValue)) {
                    // get all available menus
                    $menus = $entityManager->getRepository("CMS\Entity\Menu")->findAll();
                    foreach ($menus as $menu) {
                        $menuTitle = $menu->getTitle();
                        $menuId = $menu->getId();
                        $emptyKeyValueArray = array(
                            "value" => "",
                            "attributes" => array(
                                'data-menu' => $menuId
                            ),
                            "label" => $emptyKeyValue
                        );
                        if(! isset($valueOptions[$menuTitle]["options"])){
                            $valueOptions[$menuTitle]["label"] = $menuTitle;
                            $valueOptions[$menuTitle]["options"] = array();
                        }
                        // add root option at each menu options beginning
                        array_unshift($valueOptions[$menuTitle]["options"], $emptyKeyValueArray);
                    }
                }
                $element->setValueOptions($valueOptions);
            }
        }
        return parent::render($form);
    }

}
