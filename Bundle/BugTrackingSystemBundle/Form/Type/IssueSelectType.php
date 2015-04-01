<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'autocomplete_alias' => 'issues',
                'create_form_route'  => 'oro_bug_tracking_system_issue_create',
                'configs'            => [
                    'placeholder' => 'oro.bugtrackingsystem.issue.form.choose_issue',
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'oro_entity_create_or_select_inline';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_bug_tracking_system_issue_select';
    }
}
