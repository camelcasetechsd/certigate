<?php

use DefaultModule\Test\Controller\AbstractTestCase;

/**
 * SignControllerTest Tests for SignController
 * 
 * @package defaultModule
 * @subpackage test
 */
class SignControllerTest extends AbstractTestCase
{

   public function testInAction()
   {
       $config = $this->serviceManager->get("Config");
       var_Dump($config["doctrine"]["connection"]["orm_default"]);
       var_Dump(APPLICATION_ENV);die;
   }
    
}