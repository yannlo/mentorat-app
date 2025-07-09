<?php

namespace App\Form\Users\Mentor;

use App\Entity\Users\Mentor\Mentor;
use App\Form\Types\AvailablesType;
use App\Form\Types\DayType;
use PHPUnit\Runner\DeprecationCollector\Collector;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class AvailableAndPriceForm extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Mentor::class,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', MoneyType::class, [
                'label' => 'Entrer votre tarif (Par Heure)',
                'currency' => 'XOF', // ISO code for West African CFA franc
                'scale' => 0, // Number of decimals to display
                'grouping' => true, // Use thousands separator'

                'required' => true,
                'invalid_message' => 'Veuillez entrer un montant valide.',
                'attr' => [
                    'placeholder' => 'Ex: 15000',
                    'min' => 0,
                    'step' => 500,
                    'autocomplete' => 'off',
                    'class' => "w-full sm:w-1/2 text-xl text-right pr-6 py-1"
                ],
                'help' => 'Indiquez votre tarif horaire en F CFA. Exemple : 15000',
            ])
            ->add("availables", CollectionType::class, [
                "label" => "Lister vos jours et heure de diponibilité",
                "label_attr" => [
                    "class" => "text-lg font-semibold"
                ],
                'entry_type' => DayType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'error_bubbling' => false,
                'validation_groups' => ['mentor:availables-price'],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimetamps(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['mentor:availables-price'],
            'data_class' => Mentor::class,
        ]);
    }
}
