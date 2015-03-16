<?php

namespace Oro\Bundle\BugTrackingSystemBundle\EventListener;

use Doctrine\ORM\Event\PostFlushEventArgs;

class IssueListener
{
    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
    }
}
