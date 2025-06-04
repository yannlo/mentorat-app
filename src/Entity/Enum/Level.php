<?php

namespace App\Entity\Enum;

enum Level: string
{
    case MIDDLE_SCHOOL = 'collège';
    case HIGH_SCHOOL = 'lycée';
    case UNDERGRADUATE = 'licence';
    case GRADUATE = 'master';
    case PHD = 'doctorat';

    public function label(): string
    {
        return match ($this) {
            self::MIDDLE_SCHOOL => 'Collège',
            self::HIGH_SCHOOL => 'Lycée',
            self::UNDERGRADUATE => 'Licence',
            self::GRADUATE => 'Master',
            self::PHD => 'Doctorat',
        };
    }
    public function classes(): array
    {
        return match ($this) {
            self::MIDDLE_SCHOOL => [
                StudentClass::SIXTH,
                StudentClass::FIFTH,
                StudentClass::FOURTH,
                StudentClass::THIRD,
            ],
            self::HIGH_SCHOOL => [
                StudentClass::SECOND,
                StudentClass::FIRST,
                StudentClass::FINAL,
            ],
            self::UNDERGRADUATE => [
                StudentClass::FIRST_YEAR,
                StudentClass::SECOND_YEAR,
                StudentClass::THIRD_YEAR,
            ],
            self::GRADUATE => [
                StudentClass::FOURTH_YEAR,
                StudentClass::FIFTH_YEAR,
            ],
            self::PHD => [
                StudentClass::SIXTH_YEAR,
                StudentClass::SEVENTH_YEAR_AND_MORE
            ],
        };
    }
}
