<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => new TranslatableMessage('The name of the category'),
                'label_attr' => ['class' => 'text-white text-lg md:text-3xl font-serif font-bold'],
                'attr' => ['class' => 'text-black w-full'],
            ])
            ->add('save', SubmitType::class, [
                'label' => new TranslatableMessage('Create a category'),
                'attr' => ['class' => 'btn text-center inline-block mt-5 px-6 py-2.5 bg-white text-black font-medium text-md leading-tight uppercase rounded hover:bg-black hover:text-white hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
