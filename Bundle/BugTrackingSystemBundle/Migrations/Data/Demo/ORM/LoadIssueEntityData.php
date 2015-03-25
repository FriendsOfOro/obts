<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;

/**
 * @codeCoverageIgnore
 */
class LoadIssueEntityData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * @var array
     */
    private $fixtureSummaries = [
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit',
        'Aenean commodo ligula eget dolor',
    ];

    /**
     * @var array
     */
    private $fixtureDescriptions = [
        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.',
        'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
    ];

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\Demo\ORM\LoadUserData',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count($this->fixtureSummaries); $i++) {
            $summary = $this->fixtureSummaries[$i];
            $description = $this->fixtureDescriptions[$i];
            $issueType = $this->getRandomEntity($manager, 'OroBugTrackingSystemBundle:IssueType');
            $issuePriority = $this->getRandomEntity($manager, 'OroBugTrackingSystemBundle:IssuePriority');
            $issueResolution = $this->getRandomEntity($manager, 'OroBugTrackingSystemBundle:IssueResolution');
            $reporter = $this->getRandomEntity($manager, 'OroUserBundle:User');
            $owner = $this->getRandomEntity($manager, 'OroUserBundle:User');
            $organization = $this->getRandomEntity($manager, 'OroOrganizationBundle:Organization');

            if (!$issueType || !$issuePriority || !$issueResolution || !$reporter || !$owner || !$organization) {
                continue;
            }

            if (!$this->isIssueExist($manager, $summary)) {
                $entity = new Issue();
                $entity->setSummary($summary);
                $entity->setCode('ORO-' . ($i + 1));
                $entity->setDescription($description);
                $entity->setIssueType($issueType);
                $entity->setIssuePriority($issuePriority);
                $entity->setIssueResolution($issueResolution);
                $entity->setReporter($reporter);
                $entity->setOwner($owner);
                $entity->setCreatedAt($this->getRandomDate());
                $entity->setUpdatedAt($this->getRandomDate());
                $entity->setOrganization($organization);
                $entity->addCollaborator($reporter);
                $entity->addCollaborator($owner);

                $manager->persist($entity);
            }
        }

        $manager->flush();
    }


    /**
     * @param ObjectManager $manager
     * @param string $issueSummary
     * @return boolean
     */
    private function isIssueExist(ObjectManager $manager, $issueSummary)
    {
        $issue = $manager->getRepository('OroBugTrackingSystemBundle:Issue')->findOneBySummary($issueSummary);

        return $issue !== null;
    }

    /**
     * @param ObjectManager $manager
     * @param string $entityName
     * @return object|null
     */
    private function getRandomEntity(ObjectManager $manager, $entityName)
    {
        $count = $this->getEntityCount($manager, $entityName);
        $entity = null;

        if ($count) {
            return $manager
                ->createQueryBuilder()
                ->select('e')
                ->from($entityName, 'e')
                ->setFirstResult(rand(0, $count - 1))
                ->setMaxResults(1)
                ->orderBy('e.' . $manager->getClassMetadata($entityName)->getSingleIdentifierFieldName())
                ->getQuery()
                ->getSingleResult();
        }

        return $entity;
    }

    /**
     * @param ObjectManager $manager
     * @param string $entityName
     * @return int
     */
    private function getEntityCount(ObjectManager $manager, $entityName)
    {
        return (int) $manager
            ->createQueryBuilder()
            ->select('COUNT(e)')
            ->from($entityName, 'e')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return \DateTime
     */
    private function getRandomDate()
    {
        $result = new \DateTime();
        $result->sub(new \DateInterval(sprintf('P%dDT%dM', rand(0, 30), rand(0, 1440))));

        return $result;
    }
}
