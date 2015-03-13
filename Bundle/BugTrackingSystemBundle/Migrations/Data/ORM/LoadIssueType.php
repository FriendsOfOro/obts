<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

class LoadIssueType extends AbstractFixture
{
    /**
     * @var array
     */
    private $data = [
        ['order' => 1, 'name' => IssueType::STORY],
        ['order' => 2, 'name' => IssueType::TASK],
        ['order' => 3, 'name' => IssueType::SUB_TASK],
        ['order' => 4, 'name' => IssueType::BUG],
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $type) {
            if (!$this->isTypeExist($manager, $type['name'])) {
                $entity = new IssueType();
                $entity->setName($type['name']);
                $entity->setLabel(ucfirst($type['name']));
                $entity->setOrder($type['order']);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param string $typeName
     * @return boolean
     */
    private function isTypeExist(ObjectManager $manager, $typeName)
    {
        $type = $manager->getRepository('OroBugTrackingSystemBundle:IssueType')->findOneByName($typeName);

        return $type !== null;
    }
}
