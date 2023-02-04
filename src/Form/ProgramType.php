<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'=> new TranslatableMessage('The title of the program'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('synopsis', TextareaType::class, [
                'label' => new TranslatableMessage('Summary of the program'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['row' => 5, 'col' => 10, 'class' => 'text-black md:text-xl w-full'],
                'sanitize_html' => true,

            ])
            ->add('posterFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => new TranslatableMessage('delete the file'),
                'asset_helper' => true,
                'label' => new TranslatableMessage('Picture of the program'),
            ])
            ->add('country', TextType::class, [
                'label' => new TranslatableMessage('Country'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('year', TextType::class, [
                'label' => new TranslatableMessage('Creation year'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('category', EntityType::class, [
                'label' => new TranslatableMessage('Category'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'label' => new TranslatableMessage('The actors'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
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
