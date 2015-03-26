<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\ImportExport\TemplateFixture;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;
use Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture\IssueFixture;
use Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture\IssuePriorityFixture;
use Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture\IssueResolutionFixture;
use Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture\IssueTypeFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateEntityRegistry;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateManager;
use Oro\Bundle\OrganizationBundle\ImportExport\TemplateFixture\BusinessUnitFixture;
use Oro\Bundle\OrganizationBundle\ImportExport\TemplateFixture\OrganizationFixture;
use Oro\Bundle\UserBundle\ImportExport\TemplateFixture\UserFixture;

class IssueFixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueFixture
     */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new IssueFixture();
    }

    public function testGetEntityClass()
    {
        $this->assertEquals('Oro\Bundle\BugTrackingSystemBundle\Entity\Issue', $this->fixture->getEntityClass());
    }

    public function testCreateEntity()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $this->assertEquals(new Issue(), $this->fixture->getEntity('Story Issue'));
    }

    public function testFillEntityData()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $issue = new Issue();
        $this->fixture->fillEntityData('Story Issue', $issue);
        $this->assertEquals('ORO-1', $issue->getCode());
        $this->assertEquals('Story Issue', $issue->getSummary());
        $this->assertEquals('Story description', $issue->getDescription());
        $this->assertInstanceOf('Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType', $issue->getIssueType());
        $this->assertInstanceOf('Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority', $issue->getIssuePriority());
        $this->assertInstanceOf(
            'Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution',
            $issue->getIssueResolution()
        );
        $this->assertInstanceOf('Oro\Bundle\UserBundle\Entity\User', $issue->getReporter());
        $this->assertInstanceOf('Oro\Bundle\UserBundle\Entity\User', $issue->getOwner());
        $this->assertNull($issue->getParent());
        $this->assertInstanceOf('\DateTime', $issue->getCreatedAt());
        $this->assertInstanceOf('\DateTime', $issue->getUpdatedAt());
        $this->assertInstanceOf('Oro\Bundle\OrganizationBundle\Entity\Organization', $issue->getOrganization());
    }

    public function testGetData()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $data = $this->fixture->getData();
        $this->assertCount(1, $data);

        /**
         * @var Issue $issue
         */
        $issue = current($data);
        $this->assertInstanceOf('Oro\Bundle\BugTrackingSystemBundle\Entity\Issue', $issue);
        $this->assertEquals('ORO-1', $issue->getCode());
        $this->assertEquals('Story Issue', $issue->getSummary());
        $this->assertEquals('Story description', $issue->getDescription());
        $this->assertInstanceOf('Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType', $issue->getIssueType());
        $this->assertInstanceOf('Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority', $issue->getIssuePriority());
        $this->assertInstanceOf(
            'Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution',
            $issue->getIssueResolution()
        );
        $this->assertInstanceOf('Oro\Bundle\UserBundle\Entity\User', $issue->getReporter());
        $this->assertInstanceOf('Oro\Bundle\UserBundle\Entity\User', $issue->getOwner());
        $this->assertNull($issue->getParent());
        $this->assertInstanceOf('\DateTime', $issue->getCreatedAt());
        $this->assertInstanceOf('\DateTime', $issue->getUpdatedAt());
        $this->assertInstanceOf('Oro\Bundle\OrganizationBundle\Entity\Organization', $issue->getOrganization());
    }

    /**
     * @return TemplateManager
     */
    protected function getTemplateManager()
    {
        $securityFacade = $this->getMockBuilder('Oro\Bundle\SecurityBundle\SecurityFacade')
            ->disableOriginalConstructor()
            ->getMock();

        $entityRegistry = new TemplateEntityRegistry();
        $templateManager = new TemplateManager($entityRegistry);
        $templateManager->addEntityRepository($this->fixture);
        $templateManager->addEntityRepository(new IssuePriorityFixture());
        $templateManager->addEntityRepository(new IssueResolutionFixture());
        $templateManager->addEntityRepository(new IssueTypeFixture());
        $templateManager->addEntityRepository(new OrganizationFixture($securityFacade));
        $templateManager->addEntityRepository(new UserFixture());
        $templateManager->addEntityRepository(new BusinessUnitFixture());

        return $templateManager;
    }
}
