<?php

namespace Utilities\Service\FormSmasher;

use Utilities\Service\FormSmasher\ViewHelpers;

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
     * @param Users\Form\UserForm $form
     * @param array $elementsContainer
     * @return type
     */
    public function prepareFormForDisplay($form, $elementsContainer)
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
        $formElementErrors = $this->viewHelperManager->get(ViewHelpers::FORM_ELEMENTS_ERRORS_TEXT);

        /**
         * Form open && close tags
         */
        $form->setAttribute('enctype', 'multipart/form-data');
        $formName = $form->getAttributes()['name'];
        $elementsContainer['openTag'] = $this->viewHelperManager->get('form')->openTag($form);
        $elementsContainer['closeTag'] = $this->viewHelperManager->get('form')->closeTag();

        foreach ($form->getElements() as $element) {

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
                $elementsContainer[$name] = $formMultiCheckbox($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)), 'append');
            }
            else if ($element->getAttribute('type') === 'radio') {
                $elementsContainer[$name] = $formRadio($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)));
            }
            else if ($element->getAttribute('type') === 'button') {
                $elementsContainer[$name] = $formButton($form->get($name), $element->getValue());
//                $elementsContainer[$name] = $formButton($form->get($name), $element->setAttributes(array('id' => $formName . '_' . $name)), $element->getValue());
            }
            $elementsContainer[$name . 'Error'] = $formElementErrors($element);
            $elementsContainer[$name . 'Name'] = $name;
        }

        return $elementsContainer;
    }

}
