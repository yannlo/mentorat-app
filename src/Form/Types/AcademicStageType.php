<?php

namespace App\Form\Types;

use App\Entity\Enums\Level;
use App\Entity\Users\Mentor\AcademicStage;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AcademicStageType extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = AcademicStage::class,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('schoolName', TextType::class, [
                'label' => 'Enter the name of your school',
                'attr' => ['placeholder' => 'School Name'],
            ])

            ->add('startYear', IntegerType::class, [
                'label' => 'Select the start year',
                'attr' => [
                    'placeholder' => 'XXXX',
                    "min" => 1900,
                    "max" => date('Y'),
                ],
            ])

            ->add('endYear', IntegerType::class, [
                'label' => 'Select the end year',
                'attr' => [
                    'placeholder' => 'XXXX',
                    "min" => 1900,
                    "max" => date('Y'),
                ],

            ])

            ->add('level', EnumType::class, [
                'class' => Level::class,
                'placeholder' => 'Not selected',
                'choice_label' => function (Level $class) {
                    return $class->label();
                },
                'label' => 'Select your level',
            ])

            ->add('degreeName', TextType::class, [
                'label' => 'Enter the name of your degree',
                'attr' => ['placeholder' => 'Degree Name'],
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Enter a description of your academic stage',
                'attr' => ['placeholder' => 'Description'],
                'required' => false,
            ])

            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimetamps(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AcademicStage::class,
        ]);
    }
}
