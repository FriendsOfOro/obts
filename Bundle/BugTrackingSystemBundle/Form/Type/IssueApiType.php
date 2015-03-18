<?php

namespace Oro\Bundle\BugTrackingSystemBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oro\Bundle\SoapBundle\Form\EventListener\PatchSubscriber;

class IssueApiType extends IssueType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addEventSubscriber(new PatchSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Oro\Bundle\BugTrackingSystemBundle\Entity\Issue',
                'intention' => 'issue',
                'cascade_validation' => true,
                'csrf_protection' => false
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'issue';
    }
}
