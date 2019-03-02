<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Form\Type;

use Oro\Bundle\BugTrackingSystemBundle\Form\Type\IssueType;

class IssueTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->type = new IssueType();
    }

    public function testAddEntityFields()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $builder
            ->expects($this->at(0))
            ->method('add')
            ->with('summary', 'text')
            ->will($this->returnSelf());
        $builder
            ->expects($this->at(1))
            ->method('add')
            ->with('description', 'textarea')
            ->will($this->returnSelf());
        $builder
            ->expects($this->at(2))
            ->method('add')
            ->with('issueType', 'entity')
            ->will($this->returnSelf());
        $builder
            ->expects($this->at(3))
            ->method('add')
            ->with('issuePriority', 'entity')
            ->will($this->returnSelf());
        $builder
            ->expects($this->at(4))
            ->method('add')
            ->with('owner', 'oro_user_select')
            ->will($this->returnSelf());
        $builder
            ->expects($this->at(5))
            ->method('add')
            ->with('tags', 'oro_tag_select')
            ->will($this->returnSelf());

        $this->type->buildForm($builder, []);
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

    public function testGetName()
    {
        $this->assertEquals('oro_bug_tracking_system_issue', $this->type->getName());
    }
}
