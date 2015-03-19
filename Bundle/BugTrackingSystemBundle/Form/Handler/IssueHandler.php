<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

use Oro\Bundle\ActivityBundle\Manager\ActivityManager;
use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;

class IssueHandler
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var  ActivityManager
     */
    //protected $activityManager;

    /**
     * @var EntityRoutingHelper
     */
    //protected $entityRoutingHelper;

    /**
     * @param FormInterface       $form
     * @param Request             $request
     * @param ObjectManager       $manager
     * @param ActivityManager     $activityManager
     * @param EntityRoutingHelper $entityRoutingHelper
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        ObjectManager $manager,
        ActivityManager $activityManager,
        EntityRoutingHelper $entityRoutingHelper
    ) {
        $this->form                = $form;
        $this->request             = $request;
        $this->manager             = $manager;
        //$this->activityManager     = $activityManager;
        //$this->entityRoutingHelper = $entityRoutingHelper;
    }

    /**
     * Process form
     *
     * @param Issue $entity
     *
     * @return boolean True on successful processing, false otherwise
     */
    public function process(Issue $entity)
    {
//        $action            = $this->entityRoutingHelper->getAction($this->request);
//        $targetEntityClass = $this->entityRoutingHelper->getEntityClassName($this->request);
//        $targetEntityId    = $this->entityRoutingHelper->getEntityId($this->request);

//        if ($targetEntityClass
//            && !$entity->getId()
//            && $this->request->getMethod() === 'GET'
//            && $action === 'assign'
//            && is_a($targetEntityClass, 'Oro\Bundle\UserBundle\Entity\User', true)
//        ) {
//            $entity->setReporter(
//                $this->entityRoutingHelper->getEntity($targetEntityClass, $targetEntityId)
//            );
//            FormUtils::replaceField($this->form, 'reporter', ['read_only' => true]);
//        }

        $this->form->setData($entity);

        if (in_array($this->request->getMethod(), ['POST', 'PUT'])) {
            $this->form->submit($this->request);

            if ($this->form->isValid()) {
//                if ($targetEntityClass && $action === 'activity') {
//                    $this->activityManager->addActivityTarget(
//                        $entity,
//                        $this->entityRoutingHelper->getEntityReference($targetEntityClass, $targetEntityId)
//                    );
//                }
                $this->onSuccess($entity);

                return true;
            }
        }

        return false;
    }

    /**
     * "Success" form handler
     *
     * @param Issue $entity
     */
    protected function onSuccess(Issue $entity)
    {
        if (!$entity->getId() && $entity->getParent()) {
            $type = $this->manager
                ->getRepository('OroBugTrackingSystemBundle:IssueType')
                ->findOneByName(IssueType::SUB_TASK);

            $entity->setIssueType($type);
        }

        $this->manager->persist($entity);
        $this->manager->flush();
    }

    /**
     * Get form, that build into handler, via handler service
     *
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }
}
