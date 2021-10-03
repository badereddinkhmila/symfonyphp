<?php

namespace App\Form;

use App\Entity\PaquetRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaquetRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gateway',CheckboxType::class,[
                'label'=>'Passerelle',
                'required'=>false,
                'disabled'=>$options['dis']
            ])
            ->add('glucose_sensor',CheckboxType::class,[
                'label'=>'Glucomètre',
                'required'=>false,
                'disabled'=>$options['dis']
            ])
            ->add('oxygen_sensor',CheckboxType::class,[
                'label'=>'Oxymètre',
                'required'=>false,
                'disabled'=>$options['dis']
            ])
            ->add('blood_pressure_sensor',CheckboxType::class,[
                'label'=>'Tensiomètre',
                'required'=>false,
                'disabled'=>$options['dis']
            ])
            ->add('temperature',CheckboxType::class,[
                'label'=>'Thermomètre',
                'required'=>false,
                'disabled'=>$options['dis']
            ])
            ->add('weight',CheckboxType::class,[
                'label'=>'Balance',
                'required'=>false,
                'disabled'=>$options['dis']
            ])
            ->add('approved',CheckboxType::class,[
                'label'=>'Status',
                'required'=>false,
                'label_attr' => ['class' => 'switch-custom my-switch m-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaquetRequest::class,
            'dis'=>false
        ]);
    }
}
