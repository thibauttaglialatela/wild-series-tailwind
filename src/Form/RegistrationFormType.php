<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => new TranslatableMessage('Firstname'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('lastname', TextType::class, [
                'label' => new TranslatableMessage('Lastname'),
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('email', EmailType::class, [
                'label' => 'e-mail',
                'label_attr' => ['class' => 'text-white md:text-2xl font-serif font-bold'],
                'attr' => ['class' => 'text-black md:text-xl w-full']
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => new TranslatableMessage('Please accept the terms of use'),
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter nos condition d\'utilisation',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => new TranslatableMessage('Please create a password'),
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
