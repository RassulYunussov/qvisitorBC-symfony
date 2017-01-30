<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class qvOrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sdate', DateTimeType::class, array(
            'placeholder' => array('datetime' => 'Datetime',))
            )
            ->add('edate', DateTimeType::class, array(
            'placeholder' => array('datetime' => 'Datetime',))
            ) 
            ->add('opentime', DateTimeType::class, array(
            'placeholder' => array('datetime' => 'Datetime',))
            ) 
            ->add('closetime', DateTimeType::class, array(
            'placeholder' => array('datetime' => 'Datetime',))
            ) 
            ->add('ordertype')
            ->add('user')
            ->add('visitors')
            ->add('registered', HiddenType::class, array(
                'disabled'=>'true',
                'data'=>0))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\qvOrder'
        ));
    }
}
