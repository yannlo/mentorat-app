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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CertificationsForm extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Mentor::class,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
