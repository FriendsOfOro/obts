<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Form\Type;

use Doctrine\ORM\EntityRepository;

use Oro\Bundle\TagBundle\Form\Type\TagSelectType;
use Oro\Bundle\UserBundle\Form\Type\UserSelectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Oro\Bundle\BugTrackingSystemBundle\Entity;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                TextType::class,
                [
                    'label' => 'oro.bugtrackingsystem.issue.summary.label',
                    'required' => true
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'oro.bugtrackingsystem.issue.description.label',
                    'required' => false
                ]
            )
            ->add(
                'issueType',
                EntityType::class,
                [
                    'label' => 'oro.bugtrackingsystem.issue.issue_type.label',
                    'class' => Entity\IssueType::class,
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository
                            ->createQueryBuilder('type')
                            ->orderBy('type.entityOrder')
                            ->where('type.name != :name')
                            ->setParameter('name', Entity\IssueType::SUB_TASK);
                    }
                ]
            )
            ->add(
                'issuePriority',
                EntityType::class,
                [
                    'label' => 'oro.bugtrackingsystem.issue.issue_priority.label',
                    'class' => Entity\IssuePriority::class,
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('priority')
                            ->orderBy('priority.entityOrder');
                    }
                ]
            )
            ->add(
                'owner',
                UserSelectType::class,
                [
                    'required' => true,
                    'label' => 'oro.bugtrackingsystem.issue.owner.label',
                ]
            )
            ->add(
                'tags',
                TagSelectType::class,
                [
                    'label' => 'oro.tag.entity_plural_label',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function defaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Oro\Bundle\BugTrackingSystemBundle\Entity\Issue',
                'intention' => 'issue',
                'cascade_validation' => true,
            ]
        );
    }
}
