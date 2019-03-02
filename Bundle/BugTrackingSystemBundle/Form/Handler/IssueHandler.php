<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;
use Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType;

use Oro\Bundle\ActivityBundle\Manager\ActivityManager;
use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use Oro\Bundle\FormBundle\Utils\FormUtils;
use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class IssueHandler implements TagHandlerInterface
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @var  ActivityManager
     */
    protected $activityManager;

    /**
     * @var EntityRoutingHelper
     */
    protected $entityRoutingHelper;

    /**
     * @param FormInterface       $form
     * @param RequestStack        $requestStack
     * @param ObjectManager       $manager
     * @param ActivityManager     $activityManager
     * @param EntityRoutingHelper $entityRoutingHelper
     */
    public function __construct(
        FormInterface $form,
        RequestStack $requestStack,
        ObjectManager $manager,
        ActivityManager $activityManager,
        EntityRoutingHelper $entityRoutingHelper
    ) {
        $this->form                = $form;
        $this->requestStack        = $requestStack;
        $this->manager             = $manager;
        $this->activityManager     = $activityManager;
        $this->entityRoutingHelper = $entityRoutingHelper;
    }

    /**
     * Process form
     *
     * @param Issue $entity
     * @return boolean True on successful processing, false otherwise
     */
    public function process(Issue $entity)
    {
        $action            = $this->entityRoutingHelper->getAction($this->requestStack->getCurrentRequest());
        $targetEntityClass = $this->entityRoutingHelper->getEntityClassName($this->requestStack->getCurrentRequest());
        $targetEntityId    = $this->entityRoutingHelper->getEntityId($this->requestStack->getCurrentRequest());

        if ($targetEntityClass
            && !$entity->getId()
            && $this->requestStack->getCurrentRequest()->getMethod() === 'GET'
            && $action === 'assign'
            && (
                $targetEntityClass == 'Oro\Bundle\UserBundle\Entity\User'
                || is_subclass_of($targetEntityClass, 'Oro\Bundle\UserBundle\Entity\User')
            )
        ) {
            $entity->setOwner($this->entityRoutingHelper->getEntity($targetEntityClass, $targetEntityId));
            FormUtils::replaceField($this->form, 'owner', ['read_only' => true]);
        }

        $this->form->setData($entity);

        if (in_array($this->requestStack->getCurrentRequest()->getMethod(), ['POST', 'PUT'])) {
            $this->form->submit($this->requestStack->getCurrentRequest());

            if ($this->form->isValid()) {
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
                ->findOneBy(['name' => IssueType::SUB_TASK]);

            $entity->setIssueType($type);
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        if ($this->tagManager) {
            $this->tagManager->saveTagging($entity);
        }
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

    /**
     * {@inheritdoc}
     */
    public function setTagManager(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }
}
