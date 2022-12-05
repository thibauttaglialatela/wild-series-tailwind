<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'=>'Titre de la série',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('synopsis', TextareaType::class, [
                'label' => 'Résumé de la série',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['row' => 5, 'col' => 10, 'class' => 'text-black md:text-xl w-full'],
                'sanitize_html' => true,

            ])
            ->add('poster', TextType::class, [
                'label' => 'Illustration',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('year', TextType::class, [
                'label' => 'Année de création',
                'label_attr' => ['class' => 'Text-white Text-medium'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
