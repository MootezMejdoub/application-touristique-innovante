<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('message')
            /*->add('rec',EntityType::class,[
                'class'=>Reclamation::class,
                'choice_label'=>'id'
            ])
            /*->add('recReference',EntityType::class,[
                'class'=>Reclamation::class,
                'choice_label'=>'recReference',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
