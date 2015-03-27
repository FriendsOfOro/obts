<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\ImportExport\TemplateFixture;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution;
use Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture\IssueResolutionFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateEntityRegistry;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateManager;

class IssueResolutionFixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueResolutionFixture
     */
    protected $fixture;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->fixture = new IssueResolutionFixture();
    }

    public function testGetEntityClass()
    {
        $this->assertEquals(
            'Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution',
            $this->fixture->getEntityClass()
        );
    }

    public function testCreateEntity()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $this->assertEquals(new IssueResolution(), $this->fixture->getEntity(IssueResolution::FIXED));
    }

    public function testFillEntityData()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $resolution = new IssueResolution();
        $this->fixture->fillEntityData(IssueResolution::FIXED, $resolution);
        $this->assertEquals('fixed', $resolution->getName());
        $this->assertEquals('Fixed', $resolution->getLabel());
        $this->assertEquals(1, $resolution->getOrder());
    }

    public function testGetData()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $data = $this->fixture->getData();
        $this->assertCount(1, $data);

        /** @var IssueResolution $resolution */
        $resolution = current($data);
        $this->assertInstanceOf('Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution', $resolution);
        $this->assertEquals('fixed', $resolution->getName());
        $this->assertEquals('Fixed', $resolution->getLabel());
        $this->assertEquals(1, $resolution->getOrder());
    }

    /**
     * @return TemplateManager
     */
    protected function getTemplateManager()
    {
        $entityRegistry = new TemplateEntityRegistry();
        $templateManager = new TemplateManager($entityRegistry);
        $templateManager->addEntityRepository($this->fixture);

        return $templateManager;
    }
}
