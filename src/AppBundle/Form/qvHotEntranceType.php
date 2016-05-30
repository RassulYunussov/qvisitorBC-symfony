<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class qvHotEntranceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('patronimic')
            ->add('documentnumber')
            ->add('organization')
            ->add('attendant')
            ->add('comment')
            ->add('entrancedate', DateTimeType::class, array(
            'placeholder' => array('datetime' => 'Datetime',))
            )
            ->add('checkpoint')
            ->add('leaser')
            ->add('user')
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
