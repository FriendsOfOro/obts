<?php

namespace Oro\Bundle\BugTrackingSystemBundle\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\UnitOfWork;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;

class IssueListener
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UnitOfWork
     */
    protected $uow;

    /**
     * @var ArrayCollection
     */
    protected $queued;

    /**
     * @var boolean
     */
    protected $isInProgress = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->queued = new ArrayCollection();
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $this->initializeFromEventArgs($args);

        /**
         * @var \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue[] $entities
         */
        $entities = array_filter(
            $this->uow->getScheduledEntityInsertions(),
            function ($entity) {
                return 'Oro\Bundle\BugTrackingSystemBundle\Entity\Issue' === ClassUtils::getClass($entity);
            }
        );

        foreach ($entities as $entity) {
            if (!$entity->getId() && $this->isValuable($entity)) {
                $this->scheduleUpdate($entity);
            }
        }
    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->isInProgress || $this->queued->isEmpty()) {
            return;
        }

        $this->initializeFromEventArgs($args);

        $flushRequired = false;

        /**
         * @var \Oro\Bundle\BugTrackingSystemBundle\Entity\Issue $entity
         */
        foreach ($this->queued as $entity) {
            if (!$entity->getId() || !$this->isValuable($entity)) {
                continue;
            }

            $entity->setCode(sprintf('%s-%d', $entity->getOrganization()->getName(), $entity->getId()));
            $flushRequired = true;
        }

        if ($flushRequired) {
            $this->isInProgress = true;

            $this->em->flush($this->queued->toArray());

            $this->isInProgress = false;
        }

        $this->queued = new ArrayCollection();
    }

    /**
     * @param OnFlushEventArgs|PostFlushEventArgs $args
     */
    protected function initializeFromEventArgs($args)
    {
        $this->em = $args->getEntityManager();
        $this->uow = $this->em->getUnitOfWork();
    }

    /**
     * @param Issue $issue
     * @return boolean
     */
    protected function isValuable(Issue $issue)
    {
        return (boolean) $issue->getOrganization();
    }

    /**
     * @param Issue $issue
     */
    protected function scheduleUpdate(Issue $issue)
    {
        if (!$this->queued->contains($issue)) {
            $this->queued->add($issue);
        }
    }
}
