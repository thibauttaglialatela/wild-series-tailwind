<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', NumberType::class, [
                'label' => 'Numéro de la saison',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('year', NumberType::class, [
                'label' => 'Année',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']

            ])
            ->add('description', TextareaType::class, [
                'label' => 'résumé de la saison',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['row' => 5, 'col' => 10, 'class' => 'text-black md:text-xl w-full']
            ])
            /*->add('program', null, [
                'choice_label' => 'title',
                'label' => 'Série associée',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
                ],)*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
