<?php
namespace App\EventListener;

use App\Entity\Allowance;
use App\Entity\AllowanceStatus;
use App\Entity\Decision;
use App\Repository\DecisionRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;

class AllowanceListener
{
    public function postUpdate(Allowance $entity, LifecycleEventArgs $args)
    {
        $entityManager = $args->getObjectManager();
        /** @var DecisionRepository $repository */
        $repository = $entityManager->getRepository(Decision::class);

        if ($entity->getStatus() == AllowanceStatus::ACTIVE->value && $entity->getDecisions()->count() == 0){
            $repository->add($entity, true);
        }
    }
}