<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Randezvous;
use App\Repository\RoleRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Exception\LogicException;

class RandezVousType extends AbstractType
{   
    private $security;
    private $role_repo;

    public function __construct(Security $security, RoleRepository $role_repo){
        $this->security=$security;
        $this->role_repo=$role_repo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
            ->add('description',TextareaType::class,[
                'required'=>true,
            ])
            ->add('dated_for',DateTimeType::class,[
                'date_widget'=>'single_text',
                'time_widget'=>'single_text',
                'label' => 'Planifier pour le',
                'required'=>true,
                ])
            ->add('end_in',TimeType::class,[
                'label' => 'Finir à',
                'widget'=>'single_text',
                'required'=>true,
                'disabled'=>true
                ])
            ->add('type',TextType::class,[
                'disabled'=>true
            ]);
            $user=$this->security->getUser();
            
            if(!$user){
                throw new \LogicException(
                    'Randez-vous cannot be used without being authenticated!'
                );
            }
            

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {
                
                if($user->getIsDoctor()){
                    $pats=$user->getDoctor();
                    $label='Patients';
                }
                else {
                    $pats=$user->getPatients();
                    $label='Docteur';
                }
                $form = $event->getForm();
                $formOptions = [
                    'class' => User::class,
                    'label'=>$label,
                    'choices'=>$pats,
                    'choice_label' => function ($choice, $key, $value) {
                        return $choice->getFirstname()." ".$choice->getLastname();
                    },
                    'required'=>true,
                    'multiple' => true,
                
                ];
                if($user->getIsDoctor()){    
                    $form->add('isValid',CheckboxType::class,[
                        'label'=>'approuvée',
                        'label_attr' => ['class' => 'ml-3 switch-custom my-switch'],
                        'required'=>false

                    ]);
                }

                $form->add('parts', EntityType::class, $formOptions);
            });

    }

    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Randezvous::class,
        ]);
    }
}
