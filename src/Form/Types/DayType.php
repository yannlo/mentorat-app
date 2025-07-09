<?php

namespace App\Form\Types;

use App\Entity\Users\Mentor\Day;
use App\Entity\Enums\Day as EnumsDay;
use App\Form\Types\Collections\PeriodCollectionType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class DayType extends AbstractType
{
        use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Day::class,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', EnumType::class, [
                'class' => EnumsDay::class,
                'placeholder' => 'Not selected',
                'choice_label' => function (EnumsDay $class) {
                    return $class->label();
                },
                'label' => 'Selectionner un jour',
            ])
            ->add('periods', PeriodCollectionType::class, [
                'error_bubbling' => false,
                'label' => 'Ajouter vos heures de disponiblilités.',
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimetamps(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['mentor:availables-price'],
            'data_class' => Day::class,
        ]);
    }
}
