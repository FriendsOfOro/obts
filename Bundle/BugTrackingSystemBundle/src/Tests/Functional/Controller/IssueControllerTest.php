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

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', $this->getUrl('oro_bug_tracking_system_issue_create'));

        /** @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType $type */
        $type = $this->em
            ->getRepository('OroBugTrackingSystemBundle:IssueType')
            ->findOneBy(['name' => IssueType::STORY]);

        /** @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority $priority */
        $priority = $this->em
            ->getRepository('OroBugTrackingSystemBundle:IssuePriority')
            ->findOneBy(['name' => IssuePriority::MAJOR]);

        $form = $crawler->selectButton('Save and Close')->form();
        $form['oro_bug_tracking_system_issue_form[summary]'] = 'New issue';
        $form['oro_bug_tracking_system_issue_form[description]'] = 'New description';
        $form['oro_bug_tracking_system_issue_form[issueType]'] = $type->getId();
        $form['oro_bug_tracking_system_issue_form[issuePriority]'] = $priority->getId();
        $form['oro_bug_tracking_system_issue_form[owner]'] = '1';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue saved", $crawler->html());
    }

    /**
     * @depends testCreate
     */
    public function testCreateSubTask()
    {
        $response = $this->client->requestGrid(
            'issues-grid',
            ['issues-grid[_filter][summary][value]' => 'New issue']
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $crawler = $this->client->request(
            'GET',
            $this->getUrl('oro_bug_tracking_system_issue_create', ['id' => $result['id']])
        );

        /**
         * @var \Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority $priority
         */
        $priority = $this->em
            ->getRepository('OroBugTrackingSystemBundle:IssuePriority')
            ->findOneBy(['name' => IssuePriority::MAJOR]);

        $form = $crawler->selectButton('Save and Close')->form();
        $form['oro_bug_tracking_system_issue_form[summary]'] = 'New sub-task';
        $form['oro_bug_tracking_system_issue_form[description]'] = 'New description';
        $form['oro_bug_tracking_system_issue_form[issuePriority]'] = $priority->getId();
        $form['oro_bug_tracking_system_issue_form[owner]'] = '1';

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
            'issues-grid',
            ['issues-grid[_filter][summary][value]' => 'New issue']
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $crawler = $this->client->request(
            'GET',
            $this->getUrl('oro_bug_tracking_system_issue_update', ['id' => $result['id']])
        );

        $form = $crawler->selectButton('Save and Close')->form();
        $form['oro_bug_tracking_system_issue_form[summary]'] = 'Issue updated';
        $form['oro_bug_tracking_system_issue_form[description]'] = 'Description updated';

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
            'issues-grid',
            ['issues-grid[_filter][summary][value]' => 'Issue updated']
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $this->client->request(
            'GET',
            $this->getUrl('oro_bug_tracking_system_issue_view', ['id' => $result['id']])
        );

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issues - Activities', $result->getContent());
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
