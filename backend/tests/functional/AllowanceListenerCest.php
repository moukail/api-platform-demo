<?php

namespace App\Tests\Functional;

use App\Entity\Allowance;
use App\Entity\AllowanceStatus;
use App\Entity\Decision;
use App\Tests\FunctionalTester;
use Codeception\Scenario;

class AllowanceListenerCest
{
    /** @var \Faker\Generator */
    private $faker;

    public function beforeSuite(FunctionalTester $I)
    {
        $this->faker = \Faker\Factory::create('nl_NL');
    }

    public function _before(FunctionalTester $I)
    {

    }

    public function testPostUpdate(FunctionalTester $I, Scenario $scenario)
    {
        /** @var Allowance $allowance */
        $allowance = $I->have(Allowance::class, ['budget' => 30, 'status' => AllowanceStatus::PENDING->value]);

        $allowance->setStatus(AllowanceStatus::ACTIVE->value);
        //$I->persistEntity($allowance);
        $I->flushToDatabase();

        $I->seeInRepository(Allowance::class, [
            'id' => $allowance->getId(),
            'budget' => $allowance->getBudget(),
            'status' => AllowanceStatus::ACTIVE->value,
        ]);

        $I->seeInRepository(Decision::class, [
            'allowance' => $allowance->getId(),
            'budget' => $allowance->getBudget(),
            'expiredAt' => (new \DateTimeImmutable('+1 years'))->format('Y-m-d')
        ]);

    }
}