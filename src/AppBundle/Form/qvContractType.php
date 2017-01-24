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
             ->add('startdate', DateTimeType::class, array(
    'widget' => 'single_text',

    // do not render as type="date", to avoid HTML5 date pickers
    'html5' => false,

    // add a class that can be selected in JavaScript
    'attr' => ['class' => 'js-datepicker'],
))
             ->add('enddate', DateTimeType::class, array(
    'widget' => 'single_text',

    // do not render as type="date", to avoid HTML5 date pickers
    'html5' => false,

    // add a class that can be selected in JavaScript
    'attr' => ['class' => 'js-datepicker'],
))
           
            
            ->add('leaser')
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
