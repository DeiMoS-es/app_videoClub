<?php

namespace App\Form;

use App\Entity\Actor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ActorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'attr' => array(
                    'class' => 'bg-transparent w-60 ',
                    'placeholder' => 'Nombre del actor...'
                ),
                'label' => false
            ])
            ->add('fotoActor', FileType::class, array(
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'accept' => '.jpg, .jpeg'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Porfavor introduce un formato correcto',
                    ])
                ]
            ))
            ->add('peliculas', EntityType::class, array(
              'required' => false,
               'mapped' => false,
               'multiple' => true,
               'class' => 'App\Entity\Pelicula',
               'choice_label' => 'titulo',
            ))
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actor::class,
        ]);
    }
}
