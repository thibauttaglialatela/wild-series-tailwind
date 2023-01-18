<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', SearchType::class, [
                'label' => 'Rechercher',
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Rechercher une série',
                'attr' => [
                    'class' => 'btn text-center inline-block mt-5 px-6 py-2.5 
                    bg-white text-black font-medium text-md leading-tight uppercase rounded 
                    hover:opacity-50 focus:bg-gray-300 
                    focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 
                    active:shadow-lg transition duration-150 ease-in-out'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
