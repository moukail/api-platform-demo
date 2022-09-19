<?php

namespace App\Validator;

use App\Entity\Decision;
use App\Entity\Resident;
use App\Entity\Taxi;
use App\Repository\DecisionRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ParcelConstraintValidator extends ConstraintValidator
{
    private DecisionRepository $decisionRepository;

    public function __construct(DecisionRepository $decisionRepository)
    {
        $this->decisionRepository = $decisionRepository;
    }

    public function validate(mixed $entity, Constraint $constraint)
    {
        if (!$constraint instanceof ParcelConstraint) {
            throw new UnexpectedTypeException($constraint, ParcelConstraint::class);
        }

        if (null === $entity) {
            return;
        }

        if (!\is_object($entity)) {
            throw new UnexpectedValueException($entity, 'object');
        }

        /** @var Decision $decision */
        $decision = $entity->getDecision();
        $resident = $decision->getResident();

        /** @var Taxi $taxi */
        $taxi = $entity->getTaxi();

        if ($resident->getParcel()->getId() == $taxi->getParcel()->getId()) {
            return;
        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
