<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('password')
            ->add('gender')
            ->add('birthdate')
            ->add('avatar')
            ->add('age')
            ->add('isDoctor')
            ->add('address')
            ->add('phone')
            ->add('createdAt')
            ->add('weight')
            ->add('length')
            ->add('userRoles')
            ->add('doctor')
            ->add('patients')
            ->add('randezvouses')
            ->add('sensorGateway')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
