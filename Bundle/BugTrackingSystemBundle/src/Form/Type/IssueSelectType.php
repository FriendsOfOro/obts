<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Form\Type;

use Oro\Bundle\FormBundle\Form\Type\OroEntitySelectOrCreateInlineType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function defaultOptions(OptionsResolver $resolver)
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
        return OroEntitySelectOrCreateInlineType::class;
    }
}
