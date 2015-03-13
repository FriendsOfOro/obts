<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution;

class LoadIssueResolutionData extends AbstractFixture
{
    /**
     * @var array
     */
    private $data = [
        ['order' => 1, 'name' => IssueResolution::FIXED],
        ['order' => 2, 'name' => IssueResolution::WONT_FIX],
        ['order' => 3, 'name' => IssueResolution::DUPLICATE],
        ['order' => 4, 'name' => IssueResolution::INCOMPLETE],
        ['order' => 5, 'name' => IssueResolution::CANNOT_REPRODUCE],
        ['order' => 6, 'name' => IssueResolution::DONE],
        ['order' => 7, 'name' => IssueResolution::WONT_DO],
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $resolution) {
            if (!$this->isResolutionExist($manager, $resolution['name'])) {
                $entity = new IssueResolution();
                $entity->setName($resolution['name']);
                $entity->setLabel(ucfirst($resolution['name']));
                $entity->setOrder($resolution['order']);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param string $resolutionName
     * @return boolean
     */
    private function isResolutionExist(ObjectManager $manager, $resolutionName)
    {
        $resolution = $manager
            ->getRepository('OroBugTrackingSystemBundle:IssueResolution')
            ->findOneByName($resolutionName);

        return $resolution !== null;
    }
}
