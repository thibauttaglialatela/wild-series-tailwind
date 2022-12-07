<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'épisode',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('number', NumberType::class, [
                'label' => 'numéro',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('synopsis', TextareaType::class, [
                'label' => 'Résumé',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            /*->add('season', null, [
                'choice_label' => 'number',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
