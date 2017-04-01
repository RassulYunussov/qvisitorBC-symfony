<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class qvUserPhotoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo', FileType::class, array('label' => 'Ваше фото'))
            ->add('photodate', DateTimeType::class, array(
    'placeholder' => array(
        'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
        'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
    )
))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\qvUserPhoto'
        ));
    }
}
