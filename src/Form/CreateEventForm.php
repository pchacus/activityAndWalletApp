<?php


namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class CreateEventForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => false
            ])
            ->add('activity', ChoiceType::class,[
                'placeholder' => 'Wybierz opcje',
                'label' => false,
                'choices' =>[
                    'Aktywności' =>[
                    'Piłka nożna' => "Piłka nożna",
                    'Siatkówka' => 'Siatkówka',
                    'Koszykówka' => 'Koszykówka',
                    'Squash' => 'Squash',
                    'Jazda rowerem'=>  'Jazda rowerem',
                    'Bieganie' =>  'Bieganie'
                    ],

                ]
            ])
            ->add('placeOfMetting', TextType::class,[
                'label' => false
            ])
            ->add('date', DateTimeType::class,[
                'widget' => 'single_text',
                'label' => false
            ])
            ->add('eventPhotoName', FileType::class,[

                'mapped' => false,
                'required' => false,
                "label" => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ]
                    ])
                ]
            ])
            ->add('description', TextareaType::class,[
                'required' => false,
                'label' => false

            ])
            ->add('price', NumberType::class,[
                'label' => false
            ] )
            ->add('numberOfSeats', IntegerType::class,[
                'label' => false
            ])
            ->add('usersListId', CheckboxType::class,[
                'label' => false,
                'required' =>false

            ])
            ->getData()
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class
        ]);
    }


}