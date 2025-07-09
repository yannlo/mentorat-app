<?php

namespace App\Validator;

use App\Entity\Users\Mentor\Day;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\Common\Collections\Collection;

class AvailablesValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint): void
    {

        if (!($value instanceof Collection)) {
            return;
        }

        $names = [];

        foreach ($value as  $day) {
            $names[] = $day?->getName()?->label();
        }

        $occNames = array_count_values($names);

        foreach ($occNames as $name => $nbr) {
            // On suppose que Period a getStart() et getEnd() renvoyant DateTimeInterface
            if ($nbr > 1) {
                $this->context
                    ->buildViolation("Le $name est ajouté $nbr fois.")
                    ->addViolation();
            }
        }
        return;
    }
}
