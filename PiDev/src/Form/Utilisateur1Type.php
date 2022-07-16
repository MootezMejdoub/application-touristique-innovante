<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Utilisateur1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',
                [

                    // Register new key "empty_data" with an empty string
                    'empty_data' => ''
                ])
            ->add('prenom')
            ->add('dateNaissance')
            ->add('adresse')
            ->add('numTel')
            ->add('email')
            ->add('mdp')
            ->add('type')
            ->add('description')
            ->add('evaluation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
