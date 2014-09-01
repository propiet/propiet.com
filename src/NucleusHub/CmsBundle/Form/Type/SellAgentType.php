<?php

namespace NucleusHub\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SellAgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('first_name', 'text', array('required'  => true,'max_length'=> 30));
        $builder->add('last_name', 'text', array('required'  => true, 'max_length'=> 40));
        $builder->add('email', 'text', array('required'  => true, 'max_length'=> 40));
        $builder->add('phone', 'text', array('required'  => true, 'max_length'=> 30));
        $builder->add('address', 'text', array('required'  => true, 'max_length'=> 40));
        $builder->add('message', 'textarea', array('required'  => true, 'max_length'=> 400));
               
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
        ));
    }

    public function getName()
    {
        return 'user_sell_agent';
    }
}