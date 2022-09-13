<?php

namespace App\Form;

use App\Entity\Pelicula;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PeliculaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'attr' => array(
                    'class' => 'bg-transparent w-60 ',
                    'placeholder' => 'Titulo...'
                ),
                'label' => false
            ])
            ->add('tipo', ChoiceType::class,[
                'choices'  => Pelicula::TIPOS,
                'label' => false
            ])
            ->add('descripcion', TextareaType::class, [
                'attr' => array(
                    'class' => 'bg-transparent',
                    'placeholder' => 'Descripcion...'
                ),
                'label' => false
            ])
            ->add('foto', FileType::class, array(
                'required' => false,
                'mapped' => false
            ))
            /*->add('foto', FileType::class, [
                'label' => 'photo',
                'required' => false,
                'label' => false,
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
            ])*/
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pelicula::class,
        ]);
    }
}
