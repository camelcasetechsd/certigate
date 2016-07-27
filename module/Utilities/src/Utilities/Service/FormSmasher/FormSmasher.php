<?php

namespace Utilities\Service\FormSmasher;

use Utilities\Service\FormSmasher\ViewHelpers;
use Utilities\Service\Status;
Use Utilities\Service\String;
use Utilities\Form\Form;

class FormSmasher
{

    protected $viewHelperManager;

    public function __construct($viewhelperManager)
    {
        $this->viewHelperManager = $viewhelperManager;
    }

    /**
     * function prepare the form to be displayed as element
     * by element  
     * @param Zend\Form $form
     * @param array $elementsContainer
     * @return type
     */
    public function prepareFormForDisplay($form, $elementsContainer, $collectionArray = null)
    {
        /**
         * Helper functions
         */
        $formInput = $this->viewHelperManager->get(ViewHelpers::FORM_INPUT_TEXT);
        $formTextarea = $this->viewHelperManager->get(ViewHelpers::FORM_TEXTAREA_TEXT);
        $formPassword = $this->viewHelperManager->get(ViewHelpers::FORM_PASSWORD_TEXT);
        $formSelect = $this->viewHelperManager->get(ViewHelpers::FORM_SELECT_TEXT);
        $formNumber = $this->viewHelperManager->get(ViewHelpers::FORM_NUMBER_TEXT);
        $formTime = $this->viewHelperManager->get(ViewHelpers::FORM_TIME_TEXT);
        $formFile = $this->viewHelperManager->get(ViewHelpers::FORM_FILE_TEXT);
        $formHidden = $this->viewHelperManager->get(ViewHelpers::FORM_HIDDEN_TEXT);
        $formImage = $this->viewHelperManager->get(ViewHelpers::FORM_IMAGE_TEXT);
        $formEmail = $this->viewHelperManager->get(ViewHelpers::FORM_EMAIL_TEXT);
        $formCheckbox = $this->viewHelperManager->get(ViewHelpers::FORM_CHECKBOX_TEXT);
        $formMultiCheckbox = $this->viewHelperManager->get(ViewHelpers::FORM_MULTI_CHECKBOX_TEXT);
        $formRadio = $this->viewHelperManager->get(ViewHelpers::FORM_RADIO_TEXT);
        $formCaptcha = $this->viewHelperManager->get(ViewHelpers::FORM_CAPTCHA_TEXT);
        $formButton = $this->viewHelperManager->get(ViewHelpers::FORM_BUTTON_TEXT);
        $formSubmit = $this->viewHelperManager->get(ViewHelpers::FORM_SUBMIT_TEXT);
        $formCollection = $this->viewHelperManager->get(ViewHelpers::FORM_COLLECTION_TEXT);
        $formElementErrors = $this->viewHelperManager->get(ViewHelpers::FORM_ELEMENTS_ERRORS_TEXT);


        $form->prepare();
        /**
         * Form open && close tags
         */
        $form->setAttribute('enctype', 'multipart/form-data');
        $formName = $form->getAttributes()['name'];


        $elementsContainer['openTag'] = $this->viewHelperManager->get('form')->openTag($form);
        $elementsContainer['closeTag'] = $this->viewHelperManager->get('form')->closeTag();

        $formElements = $form->getElements();

        foreach ($formElements as $element) {

            $name = $element->getAttribute('name');
            $elementsContainer[$name . 'Label'] = $element->getLabel();

            if ($element->getAttribute('type') === 'captcha' || $element->getAttribute('name') === 'captcha') {
                $elementsContainer[$name] = $formCaptcha($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'text') {
                $elementsContainer[$name] = $formInput($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'textarea') {
                $elementsContainer[$name] = $formTextarea($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'password') {
                $elementsContainer[$name] = $formPassword($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            /**
             * to handle opt-groups in menuitems 
             */
            else if ($element->getAttribute('type') === 'select' && $element->getAttribute('name') === 'parent') {
                $valueOptions = $this->handleOptgroups($element);
                $elementsContainer[$name] = $formSelect($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)), $element->getValue(), $element->getAttributes(), $element->setValueOptions($valueOptions));
            }
            else if ($element->getAttribute('type') === 'select') {
                $elementsContainer[$name] = $formSelect($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)), $element->getValue(), $element->getAttributes(), $element->getOptions());
            }
            else if ($element->getAttribute('type') === 'select') {
                $elementsContainer[$name] = $formSelect($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)), $element->getValue(), $element->getAttributes(), $element->getOptions());
            }
            else if ($element->getAttribute('type') === 'number') {
                $elementsContainer[$name] = $formNumber($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'time') {
                $elementsContainer[$name] = $formTime($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'file') {
                $elementsContainer[$name] = $formFile($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'hidden') {
                $elementsContainer[$name] = $formHidden($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)), $element->getValue(), $element->getAttributes());
            }
            else if ($element->getAttribute('type') === 'image') {
                $elementsContainer[$name] = $formImage($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)), $element->getValue(), $element->getAttributes());
            }
            else if ($element->getAttribute('type') === 'email') {
                $elementsContainer[$name] = $formEmail($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'checkbox') {
                $elementsContainer[$name] = $formCheckbox($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'multi_checkbox') {
                $elementsContainer[$name] = $formMultiCheckbox($form->get($name), 'append', $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'radio') {
                $elementsContainer[$name] = $formRadio($form->get($name), 'append', $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'button') {
                $elementsContainer[$name] = $formButton($form->get($name), $element->getValue());
            }
            else if ($element->getAttribute('type') === 'submit') {
                $elementsContainer[$name] = $formButton($form->get($name), $element->getValue());
            }
            $elementsContainer[$name . 'Error'] = $formElementErrors($element);
            $elementsContainer[$name . 'Name'] = $name;
        }
        if (!is_null($collectionArray)) {
            foreach ($collectionArray as $collectionName) {
                $newName = $collectionName;
                $elementsContainer[$newName] = $formCollection($form->get($collectionName));
                $elementsContainer[$name . 'Error'] = $formElementErrors($element);
                $elementsContainer[$name . 'Name'] = $name;
            }
        }
        return $elementsContainer;
    }

    /**
     * function to handle categorization of optgroups and their options in menuItems 
     * @param \Zend\Form\Element $element
     * @return array
     */
    public function handleOptgroups($element)
    {
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
                    $propertyValueItems = explode(/* $delimiter = */ String::TEXT_SEPARATOR, $propertyValue);
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
            }
            else {
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
                if (!isset($valueOptions[$menuTitle]["options"])) {
                    $valueOptions[$menuTitle]["label"] = $menuTitle;
                    $valueOptions[$menuTitle]["options"] = array();
                }
                // add root option at each menu options beginning
                array_unshift($valueOptions[$menuTitle]["options"], $emptyKeyValueArray);
            }
        }
        // add empty option at options beginning
        array_unshift($valueOptions, Form::EMPTY_SELECT_VALUE);
        return $valueOptions;
    }

}
