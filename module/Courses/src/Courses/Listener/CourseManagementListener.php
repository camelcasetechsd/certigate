<?php

namespace Courses\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Gedmo\Mapping\MappedEventSubscriber;
use Utilities\Service\Status;
use Courses\Entity\Outline;
use Courses\Entity\Evaluation;

/**
 * CourseManagement listener
 * 
 * Handles Related-to-course Entities' changes related business
 *
 * @property bool $isAdminUser
 * 
 * @package courses
 * @subpackage listener
 */
class CourseManagementListener extends MappedEventSubscriber
{

    /**
     *
     * @var bool 
     */
    public $isAdminUser;

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'onFlush',
        );
    }

    /**
     * Set isAdminUser
     * 
     * @access public
     * @param bool $isAdminUser
     */
    public function setIsAdminUser($isAdminUser)
    {
        $this->isAdminUser = $isAdminUser;
    }

    /**
     * Update status for course related entities
     *
     * @param OnFlushEventArgs $eventArgs
     *
     * @return void
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $entityManager = $eventArgs->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        if ($this->isAdminUser === false) {
            foreach ($unitOfWork->getScheduledEntityUpdates() as $entity) {
                if ($entity instanceof Outline) {
                    $entity->setStatus(Status::STATUS_NOT_APPROVED);
                    $classMetadata = $entityManager->getClassMetadata(get_class($entity));
                    $unitOfWork->recomputeSingleEntityChangeSet($classMetadata, $entity);
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }

}
