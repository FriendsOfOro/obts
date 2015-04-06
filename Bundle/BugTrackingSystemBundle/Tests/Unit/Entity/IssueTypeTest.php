<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Entity;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

class IssueTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueType
     */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->entity = new IssueType();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->entity = null;
    }

    /**
     * @dataProvider settersAndGettersDataProvider
     * @param string $property
     * @param mixed $value
     */
    public function testSettersAndGetters($property, $value)
    {
        call_user_func_array([$this->entity, 'set' . ucfirst($property)], [$value]);
        $this->assertEquals($value, call_user_func_array([$this->entity, 'get' . ucfirst($property)], []));
    }

    /**
     * @return array
     */
    public function settersAndGettersDataProvider()
    {
        return [
            ['name', 'Test name'],
            ['label', 'Test label'],
            ['locale', 'en'],
            ['entityOrder', 10],
        ];
    }

    /**
     * IssueType toString test
     */
    public function testToString()
    {
        $label = 'Test label';

        $this->entity->setLabel($label);
        $this->assertEquals($label, $this->entity);
    }
}
