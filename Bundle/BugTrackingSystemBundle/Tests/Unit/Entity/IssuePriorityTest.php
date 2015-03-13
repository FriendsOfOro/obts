<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Entity;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class IssuePriorityTest extends WebTestCase
{
    /**
     * @dataProvider settersAndGettersDataProvider
     *
     * @param $property
     * @param $value
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = new IssuePriority();

        call_user_func_array([$obj, 'set' . ucfirst($property)], [$value]);
        $this->assertEquals($value, call_user_func_array([$obj, 'get' . ucfirst($property)], []));
    }

    /**
     * @return array
     */
    public function settersAndGettersDataProvider()
    {
        return [
            ['name', 'Test name'],
            ['label', 'Test label'],
            ['order', 10],
        ];
    }
}
