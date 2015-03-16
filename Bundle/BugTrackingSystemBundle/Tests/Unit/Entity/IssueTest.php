<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Entity;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\UserBundle\Entity\User;

class IssueTest extends WebTestCase
{
    /**
     * @var Issue
     */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->entity = new Issue();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->entity = null;
    }

    /**
     * Issue getter test
     */
    public function testGetId()
    {
        $id = 10;

        $this->assertNull($this->entity->getId());

        $reflection = new \ReflectionProperty(get_class($this->entity), 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->entity, $id);

        $this->assertEquals($id, $this->entity->getId());
    }

    /**
     * @dataProvider settersAndGettersDataProvider
     *
     * @param $property
     * @param $value
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
            ['summary', 'Test summary'],
            ['code', 'CODE-10'],
            ['description', 'Test Description'],
            ['issueType', new IssueType],
            ['issuePriority', new IssuePriority],
            ['issueResolution', new IssueResolution],
            ['reporter', new User],
            ['assignee', new User],
            ['parent', new Issue],
            ['createdAt', new \DateTime()],
            ['updatedAt', new \DateTime()],
            ['organization', new Organization()]
        ];
    }

    /**
     * Issue collaborators add and remove functions test
     */
    public function testCollaboratorAddAndRemoveFunctions()
    {
        $this->assertCount(0, $this->entity->getCollaborators());

        $user = new User();
        $this->entity->addCollaborator($user);
        $this->assertCount(1, $this->entity->getCollaborators());

        $this->entity->addCollaborator($user);
        $this->assertCount(1, $this->entity->getCollaborators());

        $this->entity->removeCollaborator($user);
        $this->assertCount(0, $this->entity->getCollaborators());
    }

    /**
     * Issue collaborators add and remove function test
     */
    public function testHasCollaboratorFunction()
    {
        $this->assertCount(0, $this->entity->getCollaborators());

        $user1 = new User();
        $user2 = new User();
        $this->entity->addCollaborator($user2);

        $this->assertTrue($this->entity->hasCollaborator($user2));
        $this->assertFalse($this->entity->hasCollaborator($user1));
    }

    /**
     * Issue collaborators getter test
     */
    public function testCollaboratorsGetter()
    {
        $this->assertEquals([], $this->entity->getCollaborators());
    }

    /**
     * Issue child add and remove functions test
     */
    public function testChildAddAndRemoveFunctions()
    {
        $this->assertCount(0, $this->entity->getChildren());

        $child = new Issue();
        $this->entity->addChild($child);
        $this->assertCount(1, $this->entity->getChildren());

        $this->entity->addChild($child);
        $this->assertCount(1, $this->entity->getChildren());

        $this->entity->removeChild($child);
        $this->assertCount(0, $this->entity->getChildren());
    }

    /**
     * Issue child add and remove function test
     */
    public function testHasChildFunction()
    {
        $this->assertCount(0, $this->entity->getChildren());

        $issue1 = new Issue();
        $issue2 = new Issue();
        $this->entity->addChild($issue2);

        $this->assertTrue($this->entity->hasChild($issue2));
        $this->assertFalse($this->entity->hasChild($issue1));
    }

    /**
     * Issue children getter test
     */
    public function testChildrenGetter()
    {
        $this->assertEquals([], $this->entity->getChildren());
    }

    /**
     * Issue toString test
     */
    public function testToString()
    {
        $summary = 'Test summary';

        $this->entity->setSummary($summary);
        $this->assertEquals($summary, $this->entity);
    }
}
