<?php
namespace App\Tests\Functional;

use App\Entity\Allowance;
use App\Entity\AllowanceStatus;
use App\Entity\Decision;
use App\Tests\FunctionalTester;
use Codeception\Scenario;

class ExtendDecisionCommandCest
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

    public function testCanExtendAllowance(FunctionalTester $I, Scenario $scenario)
    {
        /** @var Allowance $allowance */
        $allowance = $I->have(Allowance::class, ['budget' => 30, 'status' => AllowanceStatus::ACTIVE->value]);

        /** @var Decision $decision */
        $decision = $I->have(Decision::class, [
            'expiredAt' => new \DateTime('now'),
            'allowance' => $allowance,
        ]);

        $result = $I->runSymfonyConsoleCommand('app:decision:extend-active-allowances');

        $I->seeInRepository(Decision::class, [
            'allowance' => $allowance->getId(),
            'budget' => $allowance->getBudget(),
            'expiredAt' => (new \DateTimeImmutable('+1 years'))->format('Y-m-d')
        ]);
    }

    public function testCantExtendStoppedAllowance(FunctionalTester $I, Scenario $scenario)
    {
        /** @var Allowance $allowance */
        $allowance = $I->have(Allowance::class, ['budget' => 30, 'status' => AllowanceStatus::STOPPED->value]);

        /** @var Decision $decision */
        $decision = $I->have(Decision::class, [
            'expiredAt' => new \DateTime('now'),
            'allowance' => $allowance,
        ]);

        $result = $I->runSymfonyConsoleCommand('app:decision:extend-active-allowances');

        $I->dontSeeInRepository(Decision::class, [
            'allowance' => $allowance->getId(),
            'expiredAt' => new \DateTime('+1 years')
        ]);
    }
}