<?php

namespace FUBerlin\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventCommentType extends AbstractType {

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('comment', 'textarea', array('label' => 'Your comment'));
    }

    /**
     *  {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'FUBerlin\ProjectBundle\Model\EventComment',
        ));
    }

    /**
     *  {@inheritdoc}
     */
    public function getName() {
        return 'eventcomment';
    }

}
