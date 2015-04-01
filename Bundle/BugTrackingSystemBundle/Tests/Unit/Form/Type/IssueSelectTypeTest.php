<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Form\Type;

use Oro\Bundle\BugTrackingSystemBundle\Form\Type\IssueSelectType;

class IssueSelectTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueSelectType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->type = new IssueSelectType();
    }

    public function testSetDefaultOptions()
    {
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
        $resolver
            ->expects($this->once())
            ->method('setDefaults')
            ->with($this->isType('array'));

        $this->type->setDefaultOptions($resolver);
    }

    public function testGetParent()
    {
        $this->assertEquals('oro_entity_create_or_select_inline', $this->type->getParent());
    }

    public function testGetName()
    {
        $this->assertEquals('oro_bug_tracking_system_issue_select', $this->type->getName());
    }
}
