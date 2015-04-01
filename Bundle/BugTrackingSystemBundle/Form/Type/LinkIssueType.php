<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LinkIssueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'relatedIssue',
                'oro_bug_tracking_system_issue_select',
                [
                    'required' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_bug_tracking_system_link_issue';
    }
}
