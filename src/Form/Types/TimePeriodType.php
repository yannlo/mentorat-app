<?php

namespace App\Form\Types;

use Symfony\Component\Form\FormEvents;
use App\Entity\Users\Mentor\TimePeriod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TimePeriodType extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = TimePeriod::class,
    ) {}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', IntegerType::class, [
                "label" => false,
                'attr' => [
                    "min" => 7,
                    "max" => 23,
                ],
            ])
            ->add('end', IntegerType::class, [
                "label" => false,
                'attr' => [
                    "min" => 7,
                    "max" => 23,
                ],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimetamps(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['mentor:availables-price'],
            'data_class' => TimePeriod::class,
        ]);
    }


public function getBlockPrefix(): string
{
    return 'time_period';
}
}
