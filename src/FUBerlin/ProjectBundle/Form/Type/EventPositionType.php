<?php

namespace FUBerlin\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventPositionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('amount', 'number', array('precision' => 2));
//        $builder->add('receiptPath');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FUBerlin\ProjectBundle\Model\EventPosition',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'eventposition';
    }
}
