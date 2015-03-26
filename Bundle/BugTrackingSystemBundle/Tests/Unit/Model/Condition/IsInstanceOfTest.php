<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Model\Condition;

use Symfony\Component\PropertyAccess\PropertyPath;

use Oro\Bundle\BugTrackingSystemBundle\Model\Condition;

use Oro\Bundle\WorkflowBundle\Model\ContextAccessor;

class IsInstanceOfTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Condition\IsInstanceOf
     */
    protected $condition;

    protected function setUp()
    {
        $this->condition = new Condition\IsInstanceOf(new ContextAccessor());
    }

    /**
     * @dataProvider isAllowedDataProvider
     *
     * @param array $options
     * @param $context
     * @param $expectedResult
     */
    public function testIsAllowed(array $options, $context, $expectedResult)
    {
        $this->condition->initialize($options);
        $this->assertEquals($expectedResult, $this->condition->isAllowed($context));
    }

    public function isAllowedDataProvider()
    {
        return [
            'datetime' => [
                'options' => [new PropertyPath('[foo]'), '\DateTime'],
                'context' => ['foo' => new \DateTime()],
                'expectedResult' => true
            ],
            'string' => array(
                'options' => [new PropertyPath('[foo]'), '\DateTime'],
                'context' => ['foo' => 'bar'],
                'expectedResult' => false
            ),
        ];
    }

    /**
     * @expectedException \Oro\Bundle\WorkflowBundle\Exception\ConditionException
     * @expectedExceptionMessage Options must have 2 elements, but 0 given
     */
    public function testInitializeFailsWhenOptionNotOneElement()
    {
        $this->condition->initialize([]);
    }
}
