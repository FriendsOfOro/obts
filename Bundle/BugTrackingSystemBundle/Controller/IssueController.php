<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IssueController extends Controller
{
    /**
     * @Route(
     *      "/",
     *      name="oro_bug_tracking_system_issue_index"
     * )
     * @Template
     */
    public function indexAction()
    {
        return [];
    }
}
