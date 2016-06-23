<?php
namespace Utilities\Service\FormSmasher;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Utilities\Service\FormSmasher\FormSmasher;

class FormSmasherFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Get the view helper manager manager through our service manager
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        $formSmasher = new FormSmasher($viewHelperManager);
        return $formSmasher;
    }

}
