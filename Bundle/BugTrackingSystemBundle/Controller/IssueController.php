<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

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
     *      "/create/{id}",
     *      name="oro_bug_tracking_system_issue_create",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=null}
     * )
     * @Acl(
     *      id="oro_bug_tracking_system_issue_create",
     *      type="entity",
     *      class="OroBugTrackingSystemBundle:Issue",
     *      permission="CREATE"
     * )
     * @Template("OroBugTrackingSystemBundle:Issue:update.html.twig")
     *
     * @param null|int
     * @return array
     */
    public function createAction($id)
    {
        if ($id) {
            $story = $this->getRepository('OroBugTrackingSystemBundle:Issue')->find($id);

            if (!$story) {
                throw $this->createNotFoundException('Oro\Bundle\BtsBundle\Entity\Issue object not found.');
            }

            if (!$story->isStory()) {
                return $this->redirect($this->generateUrl('oro_bug_tracking_system_issue_view', ['id' => $id]));
            }
        }

        $issue = new Issue();

        if (isset($story)) {
            $issue->setParent($story);
        }

        $type = $this
            ->getRepository('OroBugTrackingSystemBundle:IssueType')
            ->findOneByName(IssueType::STORY);

        if ($type) {
            $issue->setIssueType($type);
        }

        $priority = $this
            ->getRepository('OroBugTrackingSystemBundle:IssuePriority')
            ->findOneByName(IssuePriority::MAJOR);

        if ($priority) {
            $issue->setIssuePriority($priority);
        }

        $formAction = $this
            ->get('oro_entity.routing_helper')
            ->generateUrlByRequest(
                'oro_bug_tracking_system_issue_create',
                $this->getRequest(),
                isset($story) ? ['id' => $story->getId()] : []
            );

        return $this->update($issue, $formAction);
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
     *
     * @param Issue $issue
     * @return array
     */
    public function updateAction(Issue $issue)
    {
        $formAction = $this->get('router')->generate('oro_bug_tracking_system_issue_update', ['id' => $issue->getId()]);

        return $this->update($issue, $formAction);
    }

    /**
     * @Route("/my", name="oro_bug_tracking_system_issue_my_issues")
     * @AclAncestor("oro_bug_tracking_system_issue_view")
     * @Template
     */
    public function myIssuesAction()
    {
        return [];
    }

    /**
     * @param string $entityName
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($entityName)
    {
        return $this->getDoctrine()->getRepository($entityName);
    }

    /**
     * @param Issue $issue
     * @param string $formAction
     * @return array
     */
    protected function update(Issue $issue, $formAction)
    {
        $saved = false;

        if ($this->get('oro_bug_tracking_system.form.handler.issue')->process($issue)) {
            if (!$this->getRequest()->get('_widgetContainer')) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('oro.bugtrackingsystem.issue.saved_message')
                );

                return $this->get('oro_ui.router')->redirectAfterSave(
                    [
                        'route' => 'oro_bug_tracking_system_issue_update',
                        'parameters' => ['id' => $issue->getId()],
                    ],
                    [
                        'route' => 'oro_bug_tracking_system_issue_view',
                        'parameters' => ['id' => $issue->getId()],
                    ]
                );
            }

            $saved = true;
        }

        return [
            'entity'     => $issue,
            'saved'      => $saved,
            'form'       => $this->get('oro_bug_tracking_system.form.handler.issue')->getForm()->createView(),
            'formAction' => $formAction,
        ];
    }
}
