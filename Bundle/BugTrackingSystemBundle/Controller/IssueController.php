<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

class IssueController extends Controller
{
    /**
     * @Route(
     *      "/",
     *      name="oro_bug_tracking_system_issue_index"
     * )
     * @Acl(
     *      id="oro_bug_tracking_system_issue_view",
     *      type="entity",
     *      class="OroBugTrackingSystemBundle:Issue",
     *      permission="VIEW"
     * )
     * @Template
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('oro_bug_tracking_system.issue.entity.class')
        ];
    }

    /**
     * @Route(
     *      "/create",
     *      name="oro_bug_tracking_system_issue_create"
     * )
     * @Acl(
     *      id="oro_bug_tracking_system_issue_create",
     *      type="entity",
     *      class="OroBugTrackingSystemBundle:Issue",
     *      permission="CREATE"
     * )
     * @Template
     *
     * @return array
     */
    public function createAction()
    {
        return [];
    }

    /**
     * @Route(
     *      "/view/{id}",
     *      name="oro_bug_tracking_system_issue_view",
     *      requirements={"id"="\d+"}
     * )
     * @AclAncestor("oro_bug_tracking_system_issue_view")
     * @Template
     *
     * @param Issue $issue
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        return ['entity' => $issue];
    }

    /**
     * @Route(
     *      "/update/{id}",
     *      name="oro_bug_tracking_system_issue_update",
     *      requirements={"id"="\d+"}
     * )
     * @Acl(
     *      id="oro_bug_tracking_system_issue_update",
     *      type="entity",
     *      class="OroBugTrackingSystemBundle:Issue",
     *      permission="EDIT"
     * )
     * @Template
     */
    public function updateAction(Issue $issue)
    {
        return [];
    }
}
