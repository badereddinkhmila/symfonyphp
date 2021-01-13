<?php

namespace App\Form;

use App\Entity\SensorGateway;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SensorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sensor_gateway_id',TextType::class,[
                'label'=>'Passerelle',
            ])
            ->add('glycose_sensor',TextType::class,[
                'label'=>'Capteur Glycémie',
            ])
            ->add('temperature_sensor',TextType::class,[
                'label'=>'Capteur température',
            ])
            ->add('oxygene_sensor',TextType::class,[
                'label'=>"Capteur niveau d'oxygéne",
            ])
            ->add('bp_sensor',TextType::class,[
                'label'=>'Capteur pression artériel',
            ])
            ->add('heart_beat_sensor',TextType::class,[
                'label'=>'Capteur battement de coeur',
            ])
            ->add('weight_sensor',TextType::class,[
                'label'=>'Capteur de poid',
            ])
            ->add('is_Active',CheckboxType::class,[
                'label'=>'Est Active',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('deploy_date',DateTimeType::class,[
                'label'=>'date de deploiement',
                'date_widget'=>'single_text',
                'time_widget'=>'single_text',
            ])
            ->add('patient_sg',EntityType::class,[
                'class' => User::class,
                'label'=>'Patient',
                'choices'=>$options['choi'],
                'choice_label' => function ($choice, $key, $value) {
                    $f_name=$choice->getFirstname();
                    $l_name=$choice->getLastname();
                    return "$f_name  $l_name";
                },
                'required'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SensorGateway::class,
            'choi'=>[],
        ]);
    }
}
