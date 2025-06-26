<?php

namespace App\Form\Users;

use App\Entity\Enums\Level;
use App\Entity\Users\Student;
use App\Entity\Enums\StudentClass;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class StudentType extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Student::class,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Enter your first name',
                'attr' => ['placeholder' => 'First Name'],

            ])
            ->add('lastname', TextType::class, [
                'label' => 'Enter your last name',
                'attr' => ['placeholder' => 'Last Name'],
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Select your birthdate',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Enter your email address',
                'attr' => [
                    'placeholder' => 'Email Address',
                    'autocomplete' => 'email'
                ],

            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please confirm your password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'first_options'  => [
                    'label' => 'Enter your password',
                    'attr' => [
                        'placeholder' => 'Password',
                        'autocomplete' => 'new-password'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirm your password',
                    'attr' => [
                        'placeholder' => 'Confirm Password',
                        'autocomplete' => 'new-password'
                    ],
                ],
            ])
            ->add('schoolName', TextType::class, [
                'label' => 'Enter your school name',
                'attr' => ['placeholder' => 'School Name'],
            ])
            ->add('level', EnumType::class, [
                'class' => Level::class,
                'placeholder' => 'Not selected',
                'choice_label' => function (Level $class) {
                    return $class->label();
                },
                'label' => 'Select your level',
            ])
            ->add('classname', EnumType::class, [
                'class' => StudentClass::class,
                'label' => 'Select your class',
                'choice_label' => function (StudentClass $class) {
                    return $class->label();
                },
                'placeholder' => 'Not selected',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'I agree to the terms',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])

            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimetamps(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
