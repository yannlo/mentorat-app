<?php

namespace App\Form\Users\Mentor;

use App\Entity\Users\Mentor\Mentor;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class AvailableAndPriceForm extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Mentor::class,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', MoneyType::class, [
                'label' => 'Entrer votre tarif (Par Heure)',
                'currency' => 'XOF', // ISO code for West African CFA franc
                'divisor' => 100, // Store as integer (cents) in DB, display as decimal
                'scale' => 2, // Number of decimals to display
                'grouping' => true, // Use thousands separator
                'required' => true,
                'invalid_message' => 'Veuillez entrer un montant valide.',
                'attr' => [
                    'placeholder' => 'Ex: 15000',
                    'min' => 0,
                    'step' => 500,
                    'autocomplete' => 'off',
                ],
                'help' => 'Indiquez votre tarif horaire en F CFA. Exemple : 15000',
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
