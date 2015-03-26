<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\ImportExport\TemplateFixture;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\ImportExport\TemplateFixture\IssuePriorityFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateEntityRegistry;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateManager;

class IssuePriorityFixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssuePriorityFixture
     */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new IssuePriorityFixture();
    }

    public function testGetEntityClass()
    {
        $this->assertEquals(
            'Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority',
            $this->fixture->getEntityClass()
        );
    }

    public function testCreateEntity()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $this->assertEquals(new IssuePriority(), $this->fixture->getEntity(IssuePriority::MAJOR));
    }

    public function testFillEntityData()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $priority = new IssuePriority();
        $this->fixture->fillEntityData(IssuePriority::MAJOR, $priority);
        $this->assertEquals('major', $priority->getName());
        $this->assertEquals('Major', $priority->getLabel());
        $this->assertEquals(1, $priority->getOrder());
    }

    public function testGetData()
    {
        $this->fixture->setTemplateManager($this->getTemplateManager());

        $data = $this->fixture->getData();
        $this->assertCount(1, $data);

        /**
         * @var IssuePriority $priority
         */
        $priority = current($data);
        $this->assertInstanceOf('Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority', $priority);
        $this->assertEquals('major', $priority->getName());
        $this->assertEquals('Major', $priority->getLabel());
        $this->assertEquals(1, $priority->getOrder());
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
