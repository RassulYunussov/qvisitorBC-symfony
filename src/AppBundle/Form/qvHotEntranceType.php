<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class qvHotEntranceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array(
                'attr'   =>  array(
                'class'   => 'form-control')))
            ->add('lastname', TextType::class, array(
                'attr'   =>  array(
                'class'   => 'form-control')))
            ->add('patronimic', TextType::class, array(
                'attr'   =>  array(
                'class'   => 'form-control')))
            ->add('documentnumber', NumberType::class, array(
                'attr'   =>  array(
                'class'   => 'form-control')))
            ->add('organization', TextType::class, array(
                'attr'   =>  array(
                'class'   => 'form-control')))
            ->add('attendant', TextType::class, array(
                'attr'   =>  array(
                'class'   => 'form-control')))
            ->add('comment', TextareaType::class, array(
                'attr'   =>  array(
                'class'   => 'form-control')))
            ->add('entrancedate', DateTimeType::class, array(
                'widget' => 'single_text', 
                'format' =>'dd/MM/yyyy hh:mm:ss',
                'html5' => false,
                'model_timezone'=>'Asia/Almaty',
                'attr' => array(
                    'class' => 'form-control'),
                'disabled' => 'true',
                'placeholder' => array('datetime' => 'Datetime',),
                ))
            ->add('checkpoint', HiddenType::class)
            ->add('user', HiddenType::class)
            ->add('leaser', TextType::class, array(
                'disabled' => 'true',
                'attr'=> array('class' => 'form-control',
           )))
            ->add('Выбрать', ButtonType::class, array(
                'attr' =>array(
                    'class'=> 'btn btn-default',
                    'label'=>'Выбрать', 
                    'data-toggle'=> 'modal',
                    'data-target'=>'#myModal'), ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\qvHotEntrance'
        ));
    }
}
