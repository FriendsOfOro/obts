<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Datagrid\Action;

use Oro\Bundle\BugTrackingSystemBundle\Datagrid\Action\IssueDeleteAction;

class IssueDeleteActionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOptions()
    {
        $action = new IssueDeleteAction();
        $options = $action->getOptions();

        $this->assertInstanceOf('Oro\Bundle\DataGridBundle\Extension\Action\Actions\AjaxAction', $action);
        $this->assertInstanceOf('Oro\Bundle\DataGridBundle\Extension\Action\ActionConfiguration', $options);
        $this->assertCount(2, $options);
        $this->assertArrayHasKey('frontend_type', $options);
        $this->assertEquals('issue-delete', $options['frontend_type']);
    }
}