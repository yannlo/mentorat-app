<?php

namespace App\Form\Users\Mentor;

use App\Entity\Users\Mentor\Mentor;
use App\Form\Types\CertificateType;
use App\Form\Types\AcademicStageType;
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

class AcademicStagesForm extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Mentor::class,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
