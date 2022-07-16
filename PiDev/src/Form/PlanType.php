<?php

namespace App\Form;

use App\Entity\Place;
use App\Entity\Plan;
use App\Entity\Utilisateur;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class PlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix')
            ->add('titre')
            ->add('description',TextareaType::class)
            ->add('nmbrplacesmax')
            ->add('nmbrplacesreste', HiddenType::class)
            ->add('datedebut',DateType::class,[
                'constraints'=>[
                    new GreaterThan(
                        array(
                            'value' => new DateTime()
                        )
                    )
                ]
            ])
            ->add('datefin',DateType::class,[
                'constraints'=>[
                    new GreaterThanOrEqual(
                        [
                            'propertyPath'=>'parent.all[datedebut].data'
                        ]
                    )
                ]
            ])
            ->add('pointdepart', EntityType::class, [
                'class'=> Place::class,
                "choice_label" => function(Place $place){
                        return sprintf("%s", $place->getNom());
                },

            ])
            ->add('note',HiddenType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plan::class,
        ]);
    }
}
