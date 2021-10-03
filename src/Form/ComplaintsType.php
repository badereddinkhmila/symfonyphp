<?php

namespace App\Form;

use App\Entity\Complaint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ComplaintsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('complaint_type',ChoiceType::class, [
                'label'=>'Type',
                'choices'=>[
                        'Application web' => [
                            "Probléme d'accées à votre espace" => "probléme d'accées",
                            "Mauvaise performance de l'application" => 'probléme de performance',
                            "Demande d'un nouveau service"=>'Suggestion'
                        ],
                        'Les dispositifs' => [
                            'Une des appareils est endommagée' => 'Panne',
                            "Probléme de configuration d'un équipement" => 'demande de configuration de la passerelle',
                        ],
                        'Autre' => 'Autre',
                ]
            ])
            ->add('complaint_description',TextareaType::class,[
                'label'=>'Description',
            ])
            ->add('is_treated',CheckboxType::class,[
                'label'=>'Est traité',
                'required'=>false,
                'label_attr' => ['class' => 'switch-custom my-switch'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Complaint::class,
        ]);
    }
}
