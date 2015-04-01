<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\ClassUtils;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueResolution;

use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

class IssueTest extends \PHPUnit_Framework_TestCase
{
    const TEST_ID = 10;

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
        $this->assertNull($this->entity->getId());

        $reflection = new \ReflectionProperty(get_class($this->entity), 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->entity, self::TEST_ID);

        $this->assertEquals(self::TEST_ID, $this->entity->getId());
    }

    /**
     * @dataProvider settersAndGettersDataProvider
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
            ['issueType', new IssueType()],
            ['issuePriority', new IssuePriority()],
            ['issueResolution', new IssueResolution()],
            ['tags', new ArrayCollection()],
            ['reporter', new User()],
            ['owner', new User()],
            ['parent', new Issue()],
            ['createdAt', new \DateTime('now', new \DateTimeZone('UTC'))],
            ['updatedAt', new \DateTime('now', new \DateTimeZone('UTC'))],
            ['organization', new Organization()],
            ['workflowItem', new WorkflowItem()],
            ['workflowStep', new WorkflowStep()],
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
        $code = 'TEST-10';

        $this->entity->setCode($code);
        $this->assertEquals($code, $this->entity);
    }

    public function testGetTags()
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->entity->getTags());
        $this->assertTrue($this->entity->getTags()->isEmpty());

        $this->entity->setTags(['tag']);
        $this->assertEquals(['tag'], $this->entity->getTags());
    }

    public function testGetTaggableId()
    {
        $reflection = new \ReflectionProperty(get_class($this->entity), 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->entity, self::TEST_ID);

        $this->assertEquals(self::TEST_ID, $this->entity->getTaggableId());
    }

    /**
     * Issue generateTemporaryCodeOnPrePersist test
     */
    public function testGenerateTemporaryCodeOnPrePersist()
    {
        $organization = new Organization();
        $organization->setName('ORO_TEST');

        $this->entity->setCode(null);
        $this->entity->setOrganization($organization);

        $this->entity->generateTemporaryCodeOnPrePersist();
        $this->assertNotNull($this->entity->getCode());
        $this->assertContains('ORO_TEST' . '-', $this->entity->getCode());
    }

    /**
     * Issue setCreatedAtAndUpdatedAtOnPrePersist test
     */
    public function testSetCreatedAtAndUpdatedAtOnPrePersist()
    {
        $this->assertNull($this->entity->getCreatedAt());
        $this->assertNull($this->entity->getUpdatedAt());

        $this->entity->setCreatedAtAndUpdatedAtOnPrePersist();
        $this->assertInstanceOf('\DateTime', $this->entity->getCreatedAt());
        $this->assertInstanceOf('\DateTime', $this->entity->getUpdatedAt());
    }

    /**
     * Issue refreshUpdatedAtOnPreUpdate test
     */
    public function testRefreshUpdatedAtOnPreUpdate()
    {
        $this->assertNull($this->entity->getUpdatedAt());

        $this->entity->refreshUpdatedAtOnPreUpdate();
        $this->assertInstanceOf('\DateTime', $this->entity->getUpdatedAt());
    }

    /**
     * Issue taggable test
     */
    public function testTaggableInterface()
    {
        $this->assertInstanceOf('Oro\Bundle\TagBundle\Entity\Taggable', $this->entity);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->entity->getTags());

        $this->assertNull($this->entity->getTaggableId());

        $ref = new \ReflectionProperty(ClassUtils::getClass($this->entity), 'id');
        $ref->setAccessible(true);
        $ref->setValue($this->entity, self::TEST_ID);

        $this->assertSame(self::TEST_ID, $this->entity->getTaggableId());

        $newCollection = new ArrayCollection();
        $this->entity->setTags($newCollection);
        $this->assertSame($newCollection, $this->entity->getTags());
    }

    public function testIsStory()
    {
        $type = new IssueType();
        $type->setName(IssueType::STORY);

        $this->entity->setIssueType($type);
        $this->assertTrue($this->entity->isStory());
    }

    public function testIsSubTask()
    {
        $type = new IssueType();
        $type->setName(IssueType::SUB_TASK);

        $this->entity->setIssueType($type);
        $this->assertTrue($this->entity->isSubTask());
    }

    /**
     * Issue related issue add and remove functions test
     */
    public function testRelatedIssueAddAndRemoveFunctions()
    {
        $this->assertCount(0, $this->entity->getRelatedIssues());

        $issue = new Issue();
        $this->entity->addRelatedIssue($issue);
        $this->assertCount(1, $this->entity->getRelatedIssues());

        $this->entity->addRelatedIssue($issue);
        $this->assertCount(1, $this->entity->getRelatedIssues());

        $this->entity->removeRelatedIssue($issue);
        $this->assertCount(0, $this->entity->getRelatedIssues());
    }

    /**
     * Issue collaborators add and remove function test
     */
    public function testHasRelatedIssueFunction()
    {
        $this->assertCount(0, $this->entity->getRelatedIssues());

        $issue1 = new Issue();
        $issue2 = new Issue();
        $this->entity->addRelatedIssue($issue2);

        $this->assertTrue($this->entity->hasRelatedIssue($issue2));
        $this->assertFalse($this->entity->hasRelatedIssue($issue1));
    }

    /**
     * Issue collaborators getter test
     */
    public function testRelatedIssuesGetter()
    {
        $this->assertEquals([], $this->entity->getRelatedIssues());
    }

}
