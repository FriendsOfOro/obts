<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Functional\Controller;

use Doctrine\ORM\EntityManager;

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
    /**
     * @var EntityManager
     */
    private $em;

    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', $this->getUrl('oro_bug_tracking_system_issue_create'));

        /**
         * @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType $type
         */
        $type = $this->em
            ->getRepository('OroBugTrackingSystemBundle:IssueType')
            ->findOneByName(IssueType::STORY);

        /**
         * @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority $priority
         */
        $priority = $this->em
            ->getRepository('OroBugTrackingSystemBundle:IssuePriority')
            ->findOneByName(IssuePriority::MAJOR);

        $form = $crawler->selectButton('Save and Close')->form();
        $form['oro_bug_tracking_system_issue[summary]'] = 'New issue';
        $form['oro_bug_tracking_system_issue[description]'] = 'New description';
        $form['oro_bug_tracking_system_issue[issueType]'] = $type->getId();
        $form['oro_bug_tracking_system_issue[issuePriority]'] = $priority->getId();
        $form['oro_bug_tracking_system_issue[owner]'] = '1';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue saved", $crawler->html());
    }

    /**
     * @depends testCreate
     */
    public function testUpdate()
    {
        $response = $this->client->requestGrid(
            'issues_grid',
            ['issues_grid[_filter][summary][value]' => 'New issue']
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $crawler = $this->client->request(
            'GET',
            $this->getUrl('oro_bug_tracking_system_issue_update', ['id' => $result['id']])
        );

        $form = $crawler->selectButton('Save and Close')->form();
        $form['oro_bug_tracking_system_issue[summary]'] = 'Issue updated';
        $form['oro_bug_tracking_system_issue[description]'] = 'Description updated';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue saved", $crawler->html());
    }

    /**
     * @depends testUpdate
     */
    public function testView()
    {
        $response = $this->client->requestGrid(
            'issues_grid',
            ['issues_grid[_filter][summary][value]' => 'Issue updated']
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $this->client->request(
            'GET',
            $this->getUrl('oro_bug_tracking_system_issue_view', ['id' => $result['id']])
        );

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        //$this->assertContains('Issue updated - Issues - Activities', $result->getContent());
    }

    /**
     * @depends testUpdate
     */
    public function testIndex()
    {
        $this->client->request('GET', $this->getUrl('oro_bug_tracking_system_issue_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issue updated', $result->getContent());
    }
}
