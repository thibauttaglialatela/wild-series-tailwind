<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'text-black md:text-xl w-full'
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new Regex(
                            pattern: "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/",
                            message: 'Le mot de passe doit comporter au minimum 6 caractères, une majuscule, une minuscule 
                        et un caractère spécial'
                        )
                    ],
                    'label' => 'Nouveau mot de passe',
                    'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                ],
                'second_options' => [
                    'label' => 'Répéter le mot de passe',
                    'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                ],
                'invalid_message' => 'Les deux mots de passe doivent être identique.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => ['class' => 'btn text-center inline-block px-6 py-2.5 bg-white text-black font-medium text-md leading-tight uppercase rounded hover:opacity-50 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
