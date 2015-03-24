<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Tests\Functional\Entity\Repository;

use Doctrine\ORM\EntityManager;

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

    public function testGetIssuesByStatus()
    {
        $aclHelper = static::$kernel->getContainer()->get('oro_security.acl_helper');

        $issues = $this->em->getRepository('OroBugTrackingSystemBundle:Issue')->getIssuesByStatus($aclHelper);

        $this->assertTrue(is_array($issues));
        $this->assertCount(0, $issues);
    }
}
