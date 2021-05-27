<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RestartPasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', PasswordType::class,[
                new Length([
                    'min' => 6,
                ])
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver ->setDefaults([
            'data_class' => User::class
        ]);
    }


}