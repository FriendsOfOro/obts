<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\BugTrackingSystemBundle\Entity\Issue;

class LinkIssueHandler
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
     * @param FormInterface $form
     * @param Request       $request
     * @param ObjectManager $manager
     */
    public function __construct(FormInterface $form, Request $request, ObjectManager $manager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->manager = $manager;
    }

    /**
     * Process form
     *
     * @param Issue $entity
     * @return boolean True on successful processing, false otherwise
     */
    public function process(Issue $entity)
    {
        if ($this->request->getMethod() == 'POST') {
            $this->form->submit($this->request);

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
        /** @var Issue $relatedIssue */
        $relatedIssue = $this->form->get('relatedIssue')->getData();

        if ($relatedIssue->getId() == $entity->getId()) {
            return;
        }

        if (!$entity->hasRelatedIssue($relatedIssue)) {
            $entity->addRelatedIssue($relatedIssue);
        }

        if (!$relatedIssue->hasRelatedIssue($entity)) {
            $relatedIssue->addRelatedIssue($entity);
        }

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
