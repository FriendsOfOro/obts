<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Bundle\Tests\Unit\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Form\Handler\LinkIssueHandler;

class LinkIssueHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ObjectManager
     */
    protected $manager;

    /**
     * @var LinkIssueHandler
     */
    protected $handler;

    /**
     * @var Issue
     */
    protected $entity;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = new Request();

        $this->manager = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->entity  = new Issue();
        $this->handler = new LinkIssueHandler($this->form, $this->request, $this->manager);
    }

    public function testProcessUnsupportedRequest()
    {
        $this->form->expects($this->never())
            ->method('submit');

        $this->assertFalse($this->handler->process($this->entity));
    }

    /**
     * @dataProvider supportedMethods
     * @param string $method
     */
    public function testProcessSupportedRequest($method)
    {
        $this->request->setMethod($method);

        $field = $this->getMock('Symfony\Component\Form\FormInterface');
        $field->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($this->entity));

         $this->form->expects($this->once())
            ->method('submit')
            ->with($this->request);
        $this->form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $this->form->expects($this->any())
            ->method('get')
            ->with('relatedIssue')
            ->will($this->returnValue($field));

        $this->assertTrue($this->handler->process($this->entity));
    }

    /**
     * @return array
     */
    public function supportedMethods()
    {
        return [['POST']];
    }

    public function testProcessValidData()
    {
        $this->request->setMethod('POST');

        $field = $this->getMock('Symfony\Component\Form\FormInterface');
        $field->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($this->entity));

        $this->form->expects($this->once())
            ->method('submit')
            ->with($this->request);
        $this->form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $this->form->expects($this->any())
            ->method('get')
            ->with('relatedIssue')
            ->will($this->returnValue($field));

        $this->manager->expects($this->never())
            ->method('persist')
            ->with($this->entity);
        $this->manager->expects($this->never())
            ->method('flush');

        $this->assertTrue($this->handler->process($this->entity));
    }
}
