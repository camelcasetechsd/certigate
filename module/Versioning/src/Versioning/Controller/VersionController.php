<?php

namespace Versioning\Controller;

use Utilities\Controller\ActionController;

/**
 * Version Controller
 * 
 * versions entries listing
 * 
 * 
 * 
 * @package versioning
 * @subpackage controller
 */
class VersionController extends ActionController {

    /**
     * Delete version
     *
     * 
     * @access public
     */
    public function deleteAction() {
        $id = $this->params('id');
        $redirect = $this->params('redirect');
        
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $log = $query->find('Versioning\Entity\LogEntry', $id);
        $objectId = $log->getObjectId();
        
        $query->remove($log);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index', 'id' => $objectId), array('name' => $redirect));
        $this->redirect()->toUrl($url);
    }
    
    /**
     * Restore version
     *
     * 
     * @access public
     */
    public function restoreAction() {
        $id = $this->params('id');
        $redirect = $this->params('redirect');
        
        $query = $this->getServiceLocator()->get('wrapperQuery');
        $log = $query->find('Versioning\Entity\LogEntry', $id);
        $objectId = $log->getObjectId();
        $object = $query->find($log->getObjectClass(), $objectId);
        
        
        $query->setEntity('Versioning\Entity\LogEntry')->entityRepository->revert($object, $log->getVersion());
        $query->setEntity($log->getObjectClass())->save($object);

        $url = $this->getEvent()->getRouter()->assemble(array('action' => 'index', 'id' => $objectId), array('name' => $redirect));
        $this->redirect()->toUrl($url);
    }
}
