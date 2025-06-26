<?php

namespace App\Entity\Enums;


enum MentorRegisterStep:string
{
    case ABOUT_STEP = 'aboutStep';
    case EDUCATION_STEP = 'academicStagesStep';
    case CERTIFICATION_STEP = 'certificationsStep';
    case AVAILABLE_AND_PRICE_STEP = 'availableAndPriceStep';
    case COMPLETED = 'completed';
    public function getOrder(): int
    {
        return match($this) {
            self::ABOUT_STEP => 1,
            self::EDUCATION_STEP => 2,
            self::CERTIFICATION_STEP => 3,
            self::AVAILABLE_AND_PRICE_STEP => 4,
            self::COMPLETED => 5,
        };
    }

    public function getLabel(): string
    {
        return match($this) {
            self::ABOUT_STEP => 'Informations Personnelles',
            self::EDUCATION_STEP => 'Parcours scolaires',
            self::CERTIFICATION_STEP => 'Certifications',
            self::AVAILABLE_AND_PRICE_STEP => 'Disponibilité et prix',
            self::COMPLETED => 'Terminer',
        };
    }

    /**
     * Summary of all
     * @return MentorRegisterStep[]
     */
    public static function all(): array {
        return [
            self::ABOUT_STEP,
            self::EDUCATION_STEP,
            self::CERTIFICATION_STEP,
            self::AVAILABLE_AND_PRICE_STEP,
            self::COMPLETED,
        ];
    }

    public function getNext(): ?self
    {
        $steps = self::all();
        $currentIndex = array_search($this, $steps, true);
        if ($currentIndex === false || $currentIndex === count($steps) - 1) {
            return null;
        }
        return $steps[$currentIndex + 1];
    }



}

