<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Functional\Controller\Api\Rest;

use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 * @dbReindex
 */
class IssueControllerTest extends WebTestCase
{
    /** @var array */
    protected $issue = [
        'summary'     => 'New issue',
        'description' => 'New description',
    ];

    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());

        $em = $this->getContainer()->get('doctrine')->getManager();

        if (!isset($this->issue['owner'])) {
            $this->issue['owner'] = $em
                ->getRepository('OroUserBundle:User')
                ->findOneBy(['username' => self::USER_NAME])
                ->getId();
        }

        if (!isset($this->issue['issuePriority'])) {
            $this->issue['issuePriority'] = $em
                ->getRepository('OroBugTrackingSystemBundle:IssuePriority')
                ->findOneByName(IssuePriority::MAJOR)
                ->getId();
        }

        if (!isset($this->issue['issueType'])) {
            $this->issue['issueType'] = $em
                ->getRepository('OroBugTrackingSystemBundle:IssueType')
                ->findOneByName(IssueType::STORY)
                ->getId();
        }
    }

    public function testCreate()
    {
        $this->client->request('POST', $this->getUrl('oro_bug_tracking_system_api_post_issue'), $this->issue);
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 201);

        $this->assertArrayHasKey('id', $issue);
        $this->assertNotEmpty($issue['id']);

        return $issue['id'];
    }

    /**
     * @depends testCreate
     */
    public function testCget()
    {
        $this->client->request('GET', $this->getUrl('oro_bug_tracking_system_api_get_issues'));
        $issues = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertCount(1, $issues);
    }

    /**
     * @depends testCreate
     */
    public function testCgetFiltering()
    {
        $baseUrl = $this->getUrl('oro_bug_tracking_system_api_get_issues');

        $date     = '2015-03-18T00:00:00+0000';
        $ownerId  = $this->issue['owner'];
        $randomId = rand($ownerId + 1, $ownerId + 100);

        $this->client->request('GET', $baseUrl . '?createdAt>' . $date);
        $this->assertCount(1, $this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $baseUrl . '?createdAt<' . $date);
        $this->assertEmpty($this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $baseUrl . '?ownerId=' . $ownerId);
        $this->assertCount(1, $this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $baseUrl . '?ownerId=' . $randomId);
        $this->assertEmpty($this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $baseUrl . '?ownerUsername=' . self::USER_NAME);
        $this->assertCount(1, $this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $baseUrl . '?ownerUsername<>' . self::USER_NAME);
        $this->assertEmpty($this->getJsonResponseContent($this->client->getResponse(), 200));
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testGet($id)
    {
        $this->client->request('GET', $this->getUrl('oro_bug_tracking_system_api_get_issue', ['id' => $id]));
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($this->issue['summary'], $issue['summary']);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testPut($id)
    {
        $updatedIssue = array_merge($this->issue, ['summary' => 'Updated summary']);

        $this->client->request(
            'PUT',
            $this->getUrl('oro_bug_tracking_system_api_put_issue', ['id' => $id]),
            $updatedIssue
        );
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oro_bug_tracking_system_api_get_issue', ['id' => $id]));

        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals('Updated summary', $issue['summary']);
        $this->assertEquals($updatedIssue['summary'], $issue['summary']);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testDelete($id)
    {
        $this->client->request('DELETE', $this->getUrl('oro_bug_tracking_system_api_delete_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oro_bug_tracking_system_api_get_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 404);
    }
}
