<?php

namespace Translation\Service\Translator;

use Zend\I18n\Translator\Translator as Translator;
use Translation\Service\Locale\Locale as ApplicationLocale;

/**
 * TranslatorHandler
 * 
 *
 * @package translation
 * @subpackage translator
 */
class TranslatorHandler extends Translator
{
    
    /**
     *
     * @var ApplicationLocale
     */
    protected $applicationLocale;

    public function getApplicationLocale()
    {
        return $this->applicationLocale;
    }
    
    public function setApplicationLocale(ApplicationLocale $applicationLocale)
    {
        $this->applicationLocale = $applicationLocale;
        $this->setLocale($applicationLocale->getCurrentLocale());
    }

}
