<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
<<<<<<< Updated upstream
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
=======
>>>>>>> Stashed changes

class qvUserPassportType extends AbstractType
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
<<<<<<< Updated upstream
            ->add('birthdate', BirthdayType::class, array(
    'placeholder' => array(
        'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
    )
))
=======
            ->add('birthdate', 'datetime')
>>>>>>> Stashed changes
            ->add('gender')
            ->add('user')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\qvUserPassport'
        ));
    }
}
