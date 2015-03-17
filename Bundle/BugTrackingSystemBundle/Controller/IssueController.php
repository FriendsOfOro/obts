<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SecurityBundle\Exception\ForbiddenException;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Handler\Context;

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
     * @Template("OroBugTrackingSystemBundle:Issue:update.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        $issue = new Issue();

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
            ->generateUrlByRequest('oro_bug_tracking_system_issue_create', $this->getRequest());

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

    /**
     * @Route(
     *      "/delete/{id}",
     *      name="oro_bug_tracking_system_issue_delete",
     *      requirements={"id"="\d+"}
     * )
     * @Acl(
     *      id="oro_bug_tracking_system_issue_delete",
     *      type="entity",
     *      class="OroBugTrackingSystemBundle:Issue",
     *      permission="EDIT"
     * )
     *
     * @param integer $id
     * @return Response
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function deleteAction($id)
    {
        $isProcessed = false;

        try {
            $this->getDeleteHandler()->handleDelete($id, $this->getManager());

            $isProcessed = true;
            $view        = View::create(null, Codes::HTTP_NO_CONTENT);
        } catch (EntityNotFoundException $notFoundEx) {
            $view = View::create(null, Codes::HTTP_NOT_FOUND);
        } catch (ForbiddenException $forbiddenEx) {
            $view = View::create(['reason' => $forbiddenEx->getReason()], Codes::HTTP_FORBIDDEN);
        }

        return $this->buildResponse($view, 'delete', ['id' => $id, 'success' => $isProcessed]);
    }

    /**
     * Gets an object responsible to delete an entity.
     *
     * @return \Oro\Bundle\SoapBundle\Handler\DeleteHandler
     */
    protected function getDeleteHandler()
    {
        return $this->get('oro_soap.handler.delete');
    }

    /**
     * @param mixed|View $data
     * @param string     $action
     * @param array      $contextValues
     * @param int        $status Used only if data was given in raw format
     *
     * @return Response
     */
    protected function buildResponse($data, $action, $contextValues = [], $status = Codes::HTTP_OK)
    {
        if ($data instanceof View) {
            $response = $this->get('fos_rest.view_handler')->handle($data);
        } else {
            $headers = isset($contextValues['headers']) ? $contextValues['headers'] : [];
            unset($contextValues['headers']);

            $response = new JsonResponse($data, $status, $headers);
        }

        $includeHandler = $this->get('oro_soap.handler.include');
        $includeHandler->handle(new Context($this, $this->get('request'), $response, $action, $contextValues));

        return $response;
    }

    /**
     * Get entity Manager
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->get('oro_bug_tracking_system.issue.manager.api');
    }
}
