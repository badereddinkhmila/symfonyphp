<?php

namespace App\Form;

use App\Entity\Randezvous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RandezvousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description',TextareaType::class,[
                'attr' => ['class' => 'text-editor'],

            ])
            ->add('dated_for',DateTimeType::class,[
                'date_widget'=>'single_text',
                'time_widget'=>'single_text',
                'label' => 'Planifier pour le',
                'required'=>true,
                ])
            ->add('end_in',DateTimeType::class,[
                'label' => 'Finir à',
                'date_widget'=>'single_text',
                'time_widget'=>'single_text',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Randezvous::class,
        ]);
    }
}
