<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\qvOrderTypeRepository;

class qvOrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sdate', DateType::class, array(
                'label'=>'Дата открытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            )
            ->add('opentime', TimeType::class, array(
                'label'=>'Время открытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            ) 
            ->add('edate', DateType::class, array(                
                'label'=>'Дата закрытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            ) 
            ->add('closetime', TimeType::class, array(
                'label'=>'Время закрытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            ) 
            ->add('ordertype', EntityType::class, array(
                'class' => 'AppBundle:qvOrderType',
                'query_builder' => function (qvOrderTypeRepository $er) {
                        return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'label'=>'Тип заявки',
                'attr'   =>  array(
                'class'   => 'form-control form-margin')))
            ->add('user', HiddenType::class)
            ->add('visitors')
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
