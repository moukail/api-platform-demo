<?php

namespace App\Validator;

use App\Entity\Decision;
use App\Repository\DecisionRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BudgetConstraintValidator extends ConstraintValidator
{
    private DecisionRepository $decisionRepository;

    public function __construct(DecisionRepository $decisionRepository)
    {
        $this->decisionRepository = $decisionRepository;
    }

    public function validate(mixed $entity, Constraint $constraint)
    {
        if (!$constraint instanceof BudgetConstraint) {
            throw new UnexpectedTypeException($constraint, BudgetConstraint::class);
        }

        if (null === $entity) {
            return;
        }

        if (!\is_object($entity)) {
            throw new UnexpectedValueException($entity, 'object');
        }

        /** @var Decision $decision */
        $decision = $entity->getDecision();

        $totalDistance = $this->decisionRepository->getTotalDistance($decision);

        if (($totalDistance + $entity->getDistance()) < $decision->getBudget()) {
            return;
        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->atPath('testpath ' . $totalDistance)
            ->addViolation();
    }
}
