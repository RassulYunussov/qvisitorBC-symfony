<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
class qvContractType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
           ->add('startdate', DateType::class, array(
    'widget' => 'single_text',
    'attr' => ['class' => 'js-datepicker'],
    'attr' => array(
                'class' => 'type_date-inline form-margin'),
))
  
           ->add('enddate', DateType::class, array(
    'widget' => 'single_text',
    'attr' => ['class' => 'js-datepicker'],
    'attr' => array(
                'class' => 'type_date-inline form-margin'),
))          ->add('leaser')
            ->add('sectors')
            ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\qvContract'
        ));
    }
}
