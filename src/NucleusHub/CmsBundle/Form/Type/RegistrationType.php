<?php

namespace NucleusHub\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('first_name', 'text', array('required'  => true,'max_length'=> 30));
        $builder->add('last_name', 'text', array('required'  => true, 'max_length'=> 40));
        $builder->add('agency_name', 'text', array('required'  => true, 'max_length'=> 100));
        $builder->add('email', 'text', array('required'  => true, 'max_length'=> 40));
        $builder->add('phone', 'text', array('required'  => true, 'max_length'=> 30));
        $builder->add('password1', 'password', array('required'  => true, 'max_length'=> 20));
        $builder->add('password2', 'password', array('required'  => true, 'max_length'=> 20));
        $builder->add('role', 'choice', array(
            'choices'   => array('ROLE_USER' => 'Particular', 'ROLE_COMPANY' => 'Inmobiliaria'),
            'required'  => true,
        ));        
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
        return 'user_registration';
    }
}