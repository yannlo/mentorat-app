<?php


namespace App\Form\Types\Collections;

use App\Form\Types\TimePeriodType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PeriodCollectionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entry_type' => TimePeriodType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'prototype' => true,
            
            'validation_groups' => ['mentor:availables-price'],
        ]);
    }

    public function getParent(): string
    {

        return CollectionType::class;
    }
}
