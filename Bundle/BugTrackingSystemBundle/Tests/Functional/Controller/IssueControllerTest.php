<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 */
class IssueControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient(
            [],
            array_merge($this->generateBasicAuthHeader(), ['HTTP_X-CSRF-Header' => 1])
        );
    }

    public function testIndex()
    {
        $this->client->request('GET', $this->getUrl('oro_bug_tracking_system_issue_index'));
        $response = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($response, 200);
    }
}
