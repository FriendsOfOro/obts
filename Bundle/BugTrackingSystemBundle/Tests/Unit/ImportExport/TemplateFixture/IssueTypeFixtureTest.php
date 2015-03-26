<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\ImportExport\TemplateFixture;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;
use Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture\IssueTypeFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateEntityRegistry;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateManager;

class IssueTypeFixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueTypeFixture
     */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new IssueTypeFixture();
    }

    public function testGetEntityClass()
    {
        $this->assertEquals('Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType', $this->fixture->getEntityClass());
    }

    public function testCreateEntity()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $this->assertEquals(new IssueType(), $this->fixture->getEntity(IssueType::STORY));
    }

    public function testFillEntityData()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $type = new IssueType();
        $this->fixture->fillEntityData(IssueType::STORY, $type);
        $this->assertEquals('story', $type->getName());
        $this->assertEquals('Story', $type->getLabel());
        $this->assertEquals(1, $type->getOrder());
    }

    public function testGetData()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $data = $this->fixture->getData();
        $this->assertCount(1, $data);

        /**
         * @var IssueType $type
         */
        $type = current($data);
        $this->assertInstanceOf('Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType', $type);
        $this->assertEquals('story', $type->getName());
        $this->assertEquals('Story', $type->getLabel());
        $this->assertEquals(1, $type->getOrder());
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
