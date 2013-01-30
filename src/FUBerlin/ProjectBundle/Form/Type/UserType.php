<?php

namespace FUBerlin\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userName', 'text');
        $builder->add('password', 'password', array('label' => 'Password'));
        $builder->add('firstName', 'text', array('label' => 'First name'));
        $builder->add('lastName', 'text', array('label' => 'Last name'));
        $builder->add('email', 'email', array('label' => 'E-Mail'));
        
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FUBerlin\ProjectBundle\Model\User',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user';
    }
}
