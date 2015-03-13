<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;

class LoadIssuePriorityData extends AbstractFixture
{
    /**
     * @var array
     */
    private $data = [
        ['order' => 1, 'name' => IssuePriority::BLOCKER],
        ['order' => 2, 'name' => IssuePriority::CRITICAL],
        ['order' => 3, 'name' => IssuePriority::MAJOR],
        ['order' => 4, 'name' => IssuePriority::MINOR],
        ['order' => 5, 'name' => IssuePriority::TRIVIAL],
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $priority) {
            if (!$this->isPriorityExist($manager, $priority['name'])) {
                $entity = new IssuePriority();
                $entity->setName($priority['name']);
                $entity->setLabel(ucfirst($priority['name']));
                $entity->setOrder($priority['order']);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param string $priorityName
     * @return boolean
     */
    private function isPriorityExist(ObjectManager $manager, $priorityName)
    {
        $priority = $manager->getRepository('OroBugTrackingSystemBundle:IssuePriority')->findOneByName($priorityName);

        return $priority !== null;
    }
}
