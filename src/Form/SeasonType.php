<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', NumberType::class, [
                'label' => new TranslatableMessage('Season number'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('year', NumberType::class, [
                'label' => new TranslatableMessage('Year'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['class' => 'text-black md:text-xl w-full']

            ])
            ->add('description', TextareaType::class, [
                'label' => new TranslatableMessage('Summary of the season'),
                'label_attr' => ['class' => 'text-white text-base md:text-2xl font-serif font-bold'],
                'attr' => ['row' => 5, 'col' => 10, 'class' => 'text-black md:text-xl w-full']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
