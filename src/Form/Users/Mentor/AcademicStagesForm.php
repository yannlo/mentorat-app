<?php

namespace App\Form\Users\Mentor;

use App\Entity\Users\Mentor\Mentor;
use App\Form\Types\AcademicStageType;
use App\Form\Types\Collections\AcademicStageCollectionType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcademicStagesForm extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Mentor::class,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('academicStages',  CollectionType::class, [
                'entry_type' => AcademicStageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                "label" => "Lister vos ecoles et diplomes",
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
            'validation_groups' => ['mentor:academic-stages'],
            'data_class' => Mentor::class,
        ]);
    }
}
