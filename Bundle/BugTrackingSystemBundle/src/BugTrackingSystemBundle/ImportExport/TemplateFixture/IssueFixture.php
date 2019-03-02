<?php

namespace Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Oro\Bundle\BugTrackingSystemBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('Story Issue');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * @param string  $key
     * @param Issue $entity
     */
    public function fillEntityData($key, $entity)
    {
        $issueTypeRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType');
        $issuePriorityRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority');
        $issueResolutionRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution');
        $userRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');
        $organizationRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');

        switch ($key) {
            case 'Story Issue':
                $entity
                    ->setCode("ORO-1")
                    ->setSummary($key)
                    ->setDescription("Story description")
                    ->setIssueType($issueTypeRepo->getEntity(IssueType::STORY))
                    ->setIssuePriority($issuePriorityRepo->getEntity(IssuePriority::MAJOR))
                    ->setIssueResolution($issueResolutionRepo->getEntity(IssueResolution::FIXED))
                    ->setReporter($userRepo->getEntity('John Doo'))
                    ->setOwner($userRepo->getEntity('John Doo'))
                    ->setParent(null)
                    ->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')))
                    ->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')))
                    ->setOrganization($organizationRepo->getEntity('default'));
                return;
            case 'Sub-Task Issue':
                $entity
                    ->setCode("ORO-2")
                    ->setSummary($key)
                    ->setDescription("Sub-Task description")
                    ->setIssueType($issueTypeRepo->getEntity(IssueType::SUB_TASK))
                    ->setIssuePriority($issuePriorityRepo->getEntity(IssuePriority::MAJOR))
                    ->setIssueResolution($issueResolutionRepo->getEntity(IssueResolution::FIXED))
                    ->setReporter($userRepo->getEntity('John Doo'))
                    ->setOwner($userRepo->getEntity('John Doo'))
                    ->setParent($this->getEntity('Story Issue'))
                    ->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')))
                    ->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')))
                    ->setOrganization($organizationRepo->getEntity('default'));
                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
