<?php

namespace App\Form;

use App\Entity\Plan;
use App\Entity\Reservation;
//use DateTimeInterface;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
//use Symfony\Component\Form\Extension\Core\Type\DateTime;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nbrplace',ChoiceType::class,array(
                'choices'=>array(
                    '1'=>1,
                    '2'=>2,
                    '3'=>3,
                    '4'=>4,
                ),
                    'attr'=>array('style'=>'width:200px'),
                    'label'=> 'Nombre de place',
                )


            )
            ->add('date_debut',DateType::class,[
                'widget'=>'single_text',
                'placeholder'=>'date de debut',
                'html5'=>false,

                //'format'=>'yyyy-mm-dd',
                'attr'=>['class'=>'js-datepicker'],
            ])

            ->add('date_fin',DateType::class,[

                'widget'=>'single_text',
                'placeholder'=>'date de debut',
                'html5'=>false,

              // 'format'=>'yyyy-mm-dd',
                'attr'=>['class'=>'js-datepicker'],

            ])

            /*->add('plan',EntityType::class,[
                'class'=>Reservation::class,
                'query_builder'=>function(EntityRepository $er){
                    return $er->createQueryBuilder('r')
                        ->join('plan','p')

                        ;

                },
                'choice_label'=>'plan'
            ])
            */

            ->add('plan')




           // ->add('idclient')

            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptchaUserRegistration',
                'constraints' => [
                    new ValidCaptcha([
                        'message' => 'Invalid captcha, please try again',
                    ]),
                ],

                    'attr'=> ['style'=>'width:auto'],
            )
            )
        ;



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
