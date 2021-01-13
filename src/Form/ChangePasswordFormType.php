<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
                'first_options' => [
                    'constraints' => [
                        new Length([
                            "min" => 10,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères'
                        ]),
                        new Regex([
                            "pattern" => "/^(?=.*\d)(?=.*[A-Z])(?=.*[@#£!$%])(?!.*(.)\1{2}).*[a-z]/m",
                            "match"=>true,
                            "message"=>"Votre mot de passe doit comporter des lettres majuscules et minuscules, un chiffre et un symbole."
                        ])
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'label' => 'Retapez le mot de passe'],
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
