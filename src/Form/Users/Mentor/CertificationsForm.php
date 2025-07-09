<?php

namespace App\Form\Users\Mentor;

use App\Entity\Users\Mentor\Mentor;
use App\Form\Types\CertificateType;
use App\Form\Types\Collections\CertificateCollectionType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CertificationsForm extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Mentor::class,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('certificates', CollectionType::class, [
                'entry_type' => CertificateType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                "label" => "Lister vos certificats",
                "label_attr" => [
                    "class" => "text-lg font-semibold"
                ]
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimetamps(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['mentor:certificates'],
            'data_class' => Mentor::class,
        ]);
    }
}
