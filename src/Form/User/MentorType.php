<?php

namespace App\Form\User;

use App\Entity\Users\Mentor;
use App\Form\CertificateType;
use App\Form\AcademicStageType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MentorType extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Mentor::class,
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
            ->add('academicStages', CollectionType::class, [
                'entry_type' => AcademicStageType::class,
                'label' => 'List your academic stages:',
                'entry_options' => [
                    "label" => false,
                    "attr" => [
                        "class" => "bg-slate-50 border-slate-300 border-2 rounded-xl p-3 mb-16"
                    ],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'data-controller' => 'form-collection',
                ],
            ])
            ->add('certificates', CollectionType::class, [
                'entry_type' => CertificateType::class,
                'label' => 'List your certificates:',
                'entry_options' => [
                    "label" => "Certificate __name__",
                    "attr" => [
                        "class" => "bg-slate-50 border-slate-300 border-2 rounded-xl p-3 mt-2 mb-6"
                    ],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'data-controller' => 'form-collection',
                ],
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
            'data_class' => Mentor::class,
        ]);
    }
}
