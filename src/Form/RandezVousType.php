<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Randezvous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RandezvousType extends AbstractType
{ 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
            ->add('description',TextareaType::class,[
                'attr' => ['class' => 'text-editor'],
                'required'=>true
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
                'required'=>true
                ])
            ->add('type',TextType::class,[
                'disabled'=>true
            ])
            ->add('parts',EntityType::class,[
                'class' => User::class,
                'label'=>$options['name'],
                'disabled'=>$options['state'],
                'choices'=>$options['choi'],
                'choice_label' => function ($choice, $key, $value) {
                    return $choice->getFirstname()." ".$choice->getLastname();
                },
                'required'=>true,
                'multiple' => true,
            ]);
    }

    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Randezvous::class,
            'name'=>'Docteur',
            'state'=>false,
            'choi'=>[],
        ]);
    }
}
