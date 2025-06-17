<?php

namespace App\Entity\Enums;

enum StudentClass: string
{
    // make a array of french's classname grouped by level
    case SIXTH = '6ème';
    case FIFTH = '5ème';
    case FOURTH = '4ème';
    case THIRD = '3ème';
    case SECOND = '2nde';
    case FIRST = '1ère';
    case FINAL = 'Terminale';
    case FIRST_YEAR = '1ère année';
    case SECOND_YEAR = '2ème année';
    case THIRD_YEAR = '3ème année';
    case FOURTH_YEAR = '4ème année';
    case FIFTH_YEAR = '5ème année';
    case SIXTH_YEAR = '6ème année';
    case SEVENTH_YEAR_AND_MORE = '7ème année et plus';

    public function label(): string
    {
        return match ($this) {
            self::SIXTH => '6ème',
            self::FIFTH => '5ème',
            self::FOURTH => '4ème',
            self::THIRD => '3ème',
            self::SECOND => '2nde',
            self::FIRST => '1ère',
            self::FINAL => 'Terminale',
            self::FIRST_YEAR => '1ère année',
            self::SECOND_YEAR => '2ème année',
            self::THIRD_YEAR => '3ème année',
            self::FOURTH_YEAR => '4ème année',
            self::FIFTH_YEAR => '5ème année',
            self::SIXTH_YEAR => '6ème année',
            self::SEVENTH_YEAR_AND_MORE => '7ème année et plus',
        };
    }
    public function level(): Level
    {
        return match ($this) {
            self::SIXTH, self::FIFTH, self::FOURTH, self::THIRD => Level::MIDDLE_SCHOOL,
            self::SECOND, self::FIRST, self::FINAL => Level::HIGH_SCHOOL,
            self::FIRST_YEAR, self::SECOND_YEAR, self::THIRD_YEAR => Level::UNDERGRADUATE,
            self::FOURTH_YEAR, self::FIFTH_YEAR, self::SIXTH_YEAR, self::SEVENTH_YEAR_AND_MORE => Level::GRADUATE,
        };
    }
}
