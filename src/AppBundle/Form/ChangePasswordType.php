<? php
namespace Acme\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\ChangePassword;
use AppBundle\Form\ChangePasswordType;
class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('oldPassword', 'password');
        $builder
        //->add('oldPassword', 'password')
        ->add('newPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'Пароли должны совпадать',
            'required' => true,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\ChangePassword\ChangePassword',
        ));
    }

    public function getName()
    {
        return 'change_passwd';
    }
}
