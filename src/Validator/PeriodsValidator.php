<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\Common\Collections\Collection;

class PeriodsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {

        if (!($value instanceof Collection)) {
            return;
        }

         $previousPeriod = null;

        foreach ($value as $index => $currentPeriod) {
            // On suppose que Period a getStart() et getEnd() renvoyant DateTimeInterface
            if ($previousPeriod !== null) {
                $prevEnd   = $previousPeriod->getEnd();
                $currStart = $currentPeriod->getStart();

                if ($prevEnd && $currStart && $prevEnd > $currStart) {
                    $this->context
                         ->buildViolation('L\'heure de debut de l\'horaire #'.($index+1) . ' doit etre supérieur à l\'heure de fin de l\'horaire #' . ($index). '.')
                         ->addViolation();
                    return;
                }
            }

            $previousPeriod = $currentPeriod;
        }

    }
}
