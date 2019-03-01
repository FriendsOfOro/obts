<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

use Oro\Bundle\WorkflowBundle\Model\WorkflowManager;

/**
 * @codeCoverageIgnore
 */
class LoadIssueEntityData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    /**
     * @var WorkflowManager
     */
    protected $workflowManager;

    /**
     * @var array
     */
    private $fixtureSummaries = [
        'Academic project on Oro'                                                   => IssueType::STORY,
        'Preparing to work'                                                         => IssueType::SUB_TASK,
        'Reading all documentation'                                                 => IssueType::SUB_TASK,
        'Analysis of the main task, decomposition on sub-tasks and planning'        => IssueType::SUB_TASK,
        'Entities (issue, issueType, issuePriority, issueResolution), tests'        => IssueType::SUB_TASK,
        'CRUD controllers and views, tests'                                         => IssueType::SUB_TASK,
        'Grid views'                                                                => IssueType::SUB_TASK,
        'API controllers (REST), tests'                                             => IssueType::SUB_TASK,
        'Translatable entities (issueType, issuePriority, issueResolution)'         => IssueType::SUB_TASK,
        'Implement issue`s workflow (OroWorkflowBundle)'                            => IssueType::SUB_TASK,
        'Issue sub-tasks flow (only for stories)'                                   => IssueType::SUB_TASK,
        'Add collaborators logic (OroUserBundle) and `updated` field logic, tests'  => IssueType::SUB_TASK,
        'Add import and export support (OroImportExportBundle)'                     => IssueType::SUB_TASK,
        'Add searching (OroSearchBundle)'                                           => IssueType::SUB_TASK,
        'Add tags support (OroTagBundle)'                                           => IssueType::SUB_TASK,
        'Dashboard widgets'                                                         => IssueType::SUB_TASK,
        'Dashboard user widget, create issue button (placeholders in OroUIBundle)'  => IssueType::SUB_TASK,
        'Add dynamic updating of data grid (OroSyncBundle, need Web Socket Server)' => IssueType::SUB_TASK,
        'Reports (OroReportBundle)'                                                 => IssueType::SUB_TASK,
        'Add notes support (OroNotebundle)'                                         => IssueType::SUB_TASK,
        'Email activity (OroEmailBundle)'                                           => IssueType::SUB_TASK,
        'Prepare demo data'                                                         => IssueType::SUB_TASK,
        'Code-style check'                                                          => IssueType::SUB_TASK,
        'Planing'                                                                   => IssueType::STORY,
    ];

    /**
     * @var array
     */
    private $workflowSteps = [
        'open',
        'start_progress',
        'resolve',
        'close',
    ];

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return ['Oro\Bundle\BugTrackingSystemBundle\Migrations\Data\Demo\ORM\LoadUserData'];
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->workflowManager = $container->get('oro_workflow.manager');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType $issueTypeStory */
        $issueTypeStory = $manager->getRepository('OroBugTrackingSystemBundle:IssueType')
            ->findOneBy(['name' => IssueType::STORY]);

        /** @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType $issueTypeSubTask */
        $issueTypeSubTask = $manager->getRepository('OroBugTrackingSystemBundle:IssueType')
            ->findOneBy(['name' => IssueType::SUB_TASK]);

        /** @var \Oro\Bundle\OrganizationBundle\Entity\Organization $organization */
        $organization = $this->getRandomEntity($manager, 'OroOrganizationBundle:Organization');
        $reporter = $manager->getRepository('OroUserBundle:User')->findOneBy(['username' => 'admin']);
        $owner = $manager->getRepository('OroUserBundle:User')->findOneBy(['username' => 'manager']);

        if (!$issueTypeStory || !$issueTypeSubTask || !$organization || !$reporter || !$owner) {
            return;
        }

        $story = null;
        foreach ($this->fixtureSummaries as $summary => $type) {
            /** @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority $issuePriority */
            $issuePriority = $this->getRandomEntity($manager, 'OroBugTrackingSystemBundle:IssuePriority');

            if (!$issuePriority) {
                continue;
            }

            $entity = new Issue();

            if ($type === IssueType::STORY) {
                $story = $entity->setIssueType($issueTypeStory);
            } else {
                $entity->setIssueType($issueTypeSubTask)->setParent($story);
            }

            if (!$this->isIssueExist($manager, $summary)) {
                $entity
                    ->setSummary($summary)
                    ->setDescription('Description for task: ' . $summary)
                    ->setIssuePriority($issuePriority)
                    ->setReporter($reporter)
                    ->setOwner($owner)
                    ->setCreatedAt($this->getRandomDate())
                    ->setUpdatedAt($this->getRandomDate())
                    ->setOrganization($organization)
                    ->addCollaborator($reporter)
                    ->addCollaborator($owner);

                $manager->persist($entity);
                $manager->flush();

                $nextStep = $this->workflowSteps[rand(0, 3)];

                if ($nextStep != 'open') {
                    $workflowItem = $this->workflowManager->getWorkflowItem($entity, 'issue_flow');
                    if ($workflowItem === null) {
                        $this->workflowManager->startWorkflow('issue_flow', $entity);
                    } else {
                        $this->workflowManager->transit($workflowItem, $nextStep);

                        $stepName = $workflowItem->getCurrentStep()->getName();

                        if (in_array($stepName, ['resolved', 'closed'])) {
                            /** @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution $issueResolution */
                            $issueResolution = $this->getRandomEntity(
                                $manager,
                                'OroBugTrackingSystemBundle:IssueResolution'
                            );
                            $entity->setIssueResolution($issueResolution);
                        }
                    }

                    $manager->flush();
                }
            }
        }
    }


    /**
     * @param ObjectManager $manager
     * @param string $issueSummary
     * @return boolean
     */
    private function isIssueExist(ObjectManager $manager, $issueSummary)
    {
        $issue = $manager->getRepository('OroBugTrackingSystemBundle:Issue')->findOneBy(['summary' => $issueSummary]);

        return $issue !== null;
    }

    /**
     * @param ObjectManager $manager
     * @param string $entityName
     * @return object|null
     */
    private function getRandomEntity(ObjectManager $manager, $entityName)
    {
        /** @var \Doctrine\ORM\EntityManager $manager */
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
        /** @var \Doctrine\ORM\EntityManager $manager */
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
        $result = new \DateTime('now', new \DateTimeZone('UTC'));
        $result->sub(new \DateInterval(sprintf('P%dDT%dM', rand(0, 30), rand(0, 1440))));

        return $result;
    }
}
