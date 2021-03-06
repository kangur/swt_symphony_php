<?php

namespace FUBerlin\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('title');
        $builder->add('place');
        $builder->add('date', 'date', array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
        ));
        $builder->add('requireReceipt','checkbox', array('required'=>false, 'label'=>'Receipt required?'));        
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FUBerlin\ProjectBundle\Model\Event',
        ));
    }

    
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'event';
    }
}
