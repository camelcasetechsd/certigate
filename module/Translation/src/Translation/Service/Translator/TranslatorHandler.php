<?php

namespace Translation\Service\Translator;

use Zend\I18n\Translator\Translator as Translator;

/**
 * TranslatorHandler
 * 
 *
 * @package translation
 * @subpackage translator
 */
class TranslatorHandler extends Translator
{

    public function __construct( )
    {
        $this->setLocale(CURRENT_LOCALE);
        $this->setFallbackLocale(DEFAULT_LOCALE);
        $this->addTranslationFile('gettext',__DIR__.'/ar_Ar.mo');
        }

}
