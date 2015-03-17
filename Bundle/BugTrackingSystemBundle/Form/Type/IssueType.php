<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Form\Type;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.bugtrackingsystem.issue.summary.label'
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label' => 'oro.bugtrackingsystem.issue.description.label'
                ]
            )
            ->add(
                'issueType',
                'entity',
                [
                    'label' => 'oro.bugtrackingsystem.issue.issue_type.label',
                    'class' => 'Oro\Bundle\BugTrackingSystemBundle\Entity\IssueType',
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('type')->orderBy('type.order');
                    }
                ]
            )
            ->add(
                'issuePriority',
                'entity',
                [
                    'label' => 'oro.bugtrackingsystem.issue.issue_priority.label',
                    'class' => 'Oro\Bundle\BugTrackingSystemBundle\Entity\IssuePriority',
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('priority')->orderBy('priority.order');
                    }
                ]
            )
            ->add(
                'owner',
                'oro_user_select',
                [
                    'required' => true,
                    'label'    => 'oro.bugtrackingsystem.issue.owner.label',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Oro\Bundle\BugTrackingSystemBundle\Entity\Issue'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_bug_tracking_system_issue';
    }
}
