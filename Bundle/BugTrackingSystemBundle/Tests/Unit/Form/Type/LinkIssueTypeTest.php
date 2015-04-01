<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Form\Type;

use Oro\Bundle\BugTrackingSystemBundle\Form\Type\LinkIssueType;

class LinkIssueTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LinkIssueType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->type = new LinkIssueType();
    }

    public function testAddEntityFields()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $builder
            ->expects($this->once())
            ->method('add')
            ->with('relatedIssue', 'oro_bug_tracking_system_issue_select', ['required' => true])
            ->will($this->returnSelf());

        $this->type->buildForm($builder, []);
    }

    public function testGetName()
    {
        $this->assertEquals('oro_bug_tracking_system_link_issue', $this->type->getName());
    }
}
