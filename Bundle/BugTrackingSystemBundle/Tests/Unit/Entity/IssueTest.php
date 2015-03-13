<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Entity;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\UserBundle\Entity\User;

class IssueTest extends WebTestCase
{
    /**
     * Issue getter test
     */
    public function testGetId()
    {
        $obj = new Issue();
        $id = 10;

        $this->assertNull($obj->getId());

        $reflection = new \ReflectionProperty(get_class($obj), 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($obj, $id);

        $this->assertEquals($id, $obj->getId());
    }

    /**
     * @dataProvider settersAndGettersDataProvider
     *
     * @param $property
     * @param $value
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = new Issue();

        call_user_func_array([$obj, 'set' . ucfirst($property)], [$value]);
        $this->assertEquals($value, call_user_func_array([$obj, 'get' . ucfirst($property)], []));
    }

    /**
     * @return array
     */
    public function settersAndGettersDataProvider()
    {
        return [
            ['summary', 'Test summary'],
            ['description', 'Test Description'],
            ['IssueType', new IssueType],
            ['IssuePriority', new IssuePriority],
            ['IssueResolution', new IssueResolution],
            ['reporter', new User],
            ['assignee', new User],
            ['parent', new Issue],
            ['createdAt', new \DateTime()],
            ['updatedAt', new \DateTime()],
        ];
    }
}
