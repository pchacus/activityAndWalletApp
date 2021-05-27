<?php


namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EventEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' =>false
            ])
            ->add('activity', ChoiceType::class,[
                'label' => false,
                'choices' => [
                    'Piłka nożna' => 'Piłka nożna',
                    'Siatkówka' => 'Siatkówka',
                    'Koszykówka' => 'Koszykówka',
                    'Squash' => 'Squash',
                    'Jazda rowerem' => 'Jazda rowerem',
                    'Bieganie' => 'Bieganie'
                ],

            ])
            ->add('placeOfMetting', TextType::class,[
                'label' => false
            ])
            ->add('date', DateType::class,[
                'widget' => 'single_text',
                'label' => false
            ])
            ->add('description', TextareaType::class,[
                'required' => false,
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
            ->add('price', NumberType::class,[
                'label' => false
            ] )
            ->add('numberOfSeats', IntegerType::class,[
                'label' => false
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