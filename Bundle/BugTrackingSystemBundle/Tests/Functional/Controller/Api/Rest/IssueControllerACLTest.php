<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Functional\Controller\Api\Rest;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class IssueControllerACLTest extends WebTestCase
{
    const USER_NAME = 'user_wo_permissions';
    const USER_PASSWORD = 'user_api_key';

    /**
     * @var int
     */
    protected static $issueId;

    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader(self::USER_NAME, self::USER_PASSWORD));

        $this->loadFixtures(
            [
                'Oro\Bundle\BugTrackingSystemBundle\Tests\Functional\Controller\Api\Rest\DataFixtures\LoadIssueData',
                'Oro\Bundle\BugTrackingSystemBundle\Tests\Functional\Controller\Api\Rest\DataFixtures\LoadUserData'
            ]
        );
    }

    protected function postFixtureLoad()
    {
        self::$issueId = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository('OroBugTrackingSystemBundle:Issue')
            ->findOneBySummary('Acl issue')
            ->getId();
    }

    public function testCreate()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $user = $em
            ->getRepository('OroUserBundle:User')
            ->findOneBy(['username' => self::USER_NAME]);

        $issuePriority = $em
            ->getRepository('OroBugTrackingSystemBundle:IssuePriority')
            ->findOneByName(IssuePriority::MAJOR);

        $issueType = $em
            ->getRepository('OroBugTrackingSystemBundle:IssueType')
            ->findOneByName(IssueType::STORY);

        $request = [
            'summary' => 'New issue',
            'description' => 'New description',
            'issuePriority' => $issuePriority->getId(),
            'issueType' => $issueType->getId(),
            'reporter' => $user->getId(),
            'owner' => $user->getId(),
        ];

        $this->client->request(
            'POST',
            $this->getUrl('oro_bug_tracking_system_api_post_issue'),
            $request,
            [],
            $this->generateWsseAuthHeader(self::USER_NAME, self::USER_PASSWORD)
        );
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 403);
    }

    /**
     * @depends testCreate
     */
    public function testCget()
    {
        $this->client->request(
            'GET',
            $this->getUrl('oro_bug_tracking_system_api_get_issues'),
            [],
            [],
            $this->generateWsseAuthHeader(self::USER_NAME, self::USER_PASSWORD)
        );
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 403);
    }

    /**
     * @depends testCreate
     */
    public function testGet()
    {
        $this->client->request(
            'GET',
            $this->getUrl('oro_bug_tracking_system_api_get_issue', ['id' => self::$issueId]),
            [],
            [],
            $this->generateWsseAuthHeader(self::USER_NAME, self::USER_PASSWORD)
        );
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 403);
    }

    /**
     * @depends testCreate
     */
    public function testPut()
    {
        $updatedTask = ['subject' => 'Updated summary'];
        $this->client->request(
            'PUT',
            $this->getUrl('oro_bug_tracking_system_api_put_issue', ['id' => self::$issueId]),
            $updatedTask,
            [],
            $this->generateWsseAuthHeader(self::USER_NAME, self::USER_PASSWORD)
        );
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 403);
    }

    /**
     * @depends testCreate
     */
    public function testDelete()
    {
        $this->client->request(
            'DELETE',
            $this->getUrl('oro_bug_tracking_system_api_delete_issue', ['id' => self::$issueId]),
            [],
            [],
            $this->generateWsseAuthHeader(self::USER_NAME, self::USER_PASSWORD)
        );
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 403);
    }
}
