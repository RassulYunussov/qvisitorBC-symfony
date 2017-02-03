<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class qvRegisrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', 'text', ['label'=>'Логин'])
            ->add('password', 'password', ['label'=>'Пароль'])
            //->add('disabled', 'number', 'label'=>'Состояние (активный/неактивный)')
            //->add('leaser', 'text', 'label'=>'Арендатор')
            ->add('roles', 'text', ['label'=>'Роль'])
            ->add('Сохранить', 'submit', ['label' => 'Зарегистрировать'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\qvUser'
        ));
    }
}
