<?php

namespace App\Form\Types;

use App\Entity\Users\Mentor\Certificate;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificateType extends AbstractType
{
    use \App\Form\Traits\AttachTimestampTrait;

    public function __construct(
        private readonly string $classname = Certificate::class,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => 'Enter the name of the certificate',
                'attr' => ['placeholder' => 'Certificate Name'],
            ])
            ->add('issueDate', DateType::class, [
                'label' => 'Select the issue date',
            ])
            ->add('issuer', TextType::class, [
                'label' => 'Enter the name of the issuer',
                'attr' => ['placeholder' => 'Issuer Name'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Enter a description of the certificate',
                'attr' => ['placeholder' => 'Description'],
                'required' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimetamps(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['mentor:certificates'],
            'data_class' => Certificate::class,
        ]);
    }
}
