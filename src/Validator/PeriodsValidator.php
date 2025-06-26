<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PeriodsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!is_array($value)) {
            return;
        }

        if (count($value) <= 0) {
            $this->context->buildViolation('Au moins une période doit etre enregistrer par jours.')
                ->addViolation();
            return;
        }

        // 1. Vérifie qu'il n'y a pas plus de 3 périodes
        if (count($value) > 3) {
            $this->context->buildViolation('Vous ne pouvez pas avoir plus de 3 périodes.')
                ->addViolation();
            return;
        }

        // 2. Vérifie l'ordre des périodes
        for ($i = 1; $i < count($value); $i++) {
            $prev = $value[$i - 1];
            $curr = $value[$i];

            // On suppose que chaque période est un tableau ou objet avec 'end' et 'start'
            $prevEnd = $prev['end'] ?? ($prev->end ?? null);
            $currStart = $curr['start'] ?? ($curr->start ?? null);

            if ($prevEnd && $currStart && $prevEnd > $currStart) {
                $this->context->buildViolation('La date de fin d\'une période ne doit pas dépasser la date de début de la période suivante.')
                    ->addViolation();
                return;
            }
        }
    }
}