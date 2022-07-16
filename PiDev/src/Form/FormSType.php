<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;



class FormSType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'constraints' => new NotBlank(),

            ])
            ->add('prenom',TextType::class, [
        'constraints' => new NotBlank(),
    ])
                ->add('dateNaissance',DateType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('adresse',TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('numTel',TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a phone number',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your phone number should be composed of {{ limit }} numbers',
                        // max length allowed by Symfony for security reasons
                        'max' => 8,
                    ]),
                ],

            ])

            ->add('email',TextType::class, [
                'constraints' => new NotBlank(),
            ])
           /* ->add('type', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Guide'=>'ROLE_GUIDE' ,
                    'Simple User'=>'ROLE_USER' ,

                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please choose a role',
                    ]),
                ],
                'expanded' => true,
                'multiple' => false
            ])*/

            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Guide' => 'ROLE_GUIDE',
                    'Utilisateur' => 'ROLE_USER',
                    'Client' => 'ROLE_CLIENT',

                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles'
            ])



            ->add('confmdp',PasswordType::class, [
                'mapped' => false,
                'constraints' => new NotBlank([
                'message' => 'Please confirm your password',
            ]),


            ])

            ->add('mdp',PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                   /* new EqualTo(
                        array(
                            'propertyPath' => 'confmdp')),*/


                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*\d).{6,}$/i',
                        'message' => 'New password is required to be minimum 6 chars in length and to include at least one letter and one number.',
                    ]),




                ],
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
