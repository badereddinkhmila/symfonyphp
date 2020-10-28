<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\File;

class UserFormType extends AbstractType
{

    public function getConfig($label,$classes){
            return[
                'label'=>$label,
                'attr'=>[
                    'class'=>$classes,
                    ]
                ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('firstname',TextType::class,$this->getConfig('Prénom',''))
            ->add('lastname',TextType::class,$this->getConfig('Nom',''))
            ->add('email',EmailType::class)
            ->add('password',PasswordType::class)
            
            ->add('birthdate',BirthdayType::class,[
                'widget' => 'single_text',
            ])
            ->add('gender',ChoiceType::class, [
                'choices'=>[
                    'Homme'=>'Homme',
                    'Femme'=>'Femme'
            ]
            ])
            ->add('age',IntegerType::class,[
                'required'=>false,
            ])->add('avatar',FileType::class,[
                'attr'=>[
                    'class'=>'form-control-file',
                ],
                
                'label'=>'Photo de profil',
                'required'=>false,
                'constraints' => [new File([
                                            'maxSize' => '200K',
                                            'maxSizeMessage'=>'Le fichier est grand,la taille maximale est {{ limit }} {{ suffix }}',
                                            'mimeTypes'=>['application/jpeg','application/png','application/jpg'],
                                            ])]
                
            ])
            ->add('address',TextType::class,[

            ])
            ->add('phone',TelType::class,[
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
