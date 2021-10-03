<?php

namespace App\Form;


use App\Entity\SensorGateway;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\File;

class UserFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('firstname',TextType::class,[
                'label'=>'Prénom'
            ])
            ->add('lastname',TextType::class,[
                'label'=>'Nom'
            ])
            ->add('email',EmailType::class,[
                'label'=>'E-mail'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation'],
            ])
            ->add('gender',ChoiceType::class, [
                'label'=>'Genre',
                'choices'=>[
                    'Homme'=>'Homme',
                    'Femme'=>'Femme'
            ]
            ])
            ->add('birthdate',BirthdayType::class,[
                'label'=>'Date de naissance',
                'placeholder' => 'Choisir date',
                'widget' => 'single_text',
            ])
           ->add('avatar',FileType::class,[
                'attr'=>['class'=>'form-control-file'],
                'label'=>'Photo de profil',
                'required'=>false,
                'constraints' => [new File([
                                            'maxSize' => '200K',
                                            'maxSizeMessage'=>'Le fichier est grand,la taille maximale est {{ limit }} {{ suffix }}',
                                            'mimeTypes'=>['image/jpeg','image/png','image/jpg'],
                                            ])]
                
            ])
            ->add('address',TextType::class,[
                'label'=>'Adresse'
            ])
            ->add('phone',TelType::class,[
                'label'=>'Numéro portable'
                ])

            ->add('weight',NumberType::class,[
                'label'=>'Poids(kg)',
                'required'=>false,
            ])
            ->add('length',NumberType::class,[
                'label'=>'Taille(m)',
                'required'=>false,
            ])
            ->add('ever_married',RadioType::class,[
            'label'=>'Déja marié(e)',
                'required'=>false,
            ])
            ->add('ever_smoked',RadioType::class,[
            'label'=>'Déja fumé(e)',
                'required'=>false,
            ])
            ->add('active',CheckboxType::class,[
                'label'=>'Active',
                'label_attr' => ['class' => 'switch-custom my-switch'],
                'required'=>false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain'=>'forms'
        ]);
    }
}
