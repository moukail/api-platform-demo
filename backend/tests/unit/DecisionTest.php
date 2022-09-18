<?php

namespace App\Tests\Unit;

use App\Entity\Allowance;
use App\Entity\Decision;
use App\Tests\UnitTester;
use Codeception\Specify;
use Symfony\Component\Validator\Validator\TraceableValidator;

class DecisionTest extends \Codeception\Test\Unit
{
    use Specify;

    protected UnitTester $tester;
    private \Faker\Generator $faker;
    private TraceableValidator $validator;

    /**
     * @specify
     */
    private Decision $decision;

    protected function _before()
    {
        $this->faker = \Faker\Factory::create('nl_NL');
        $this->validator = $this->tester->grabService('validator');
    }

    public function testCanMakeDecision()
    {
        $this->decision = new Decision();

        //$this->specify("Decision is ok", function() {

        /** @var Allowance $allowance */
        $allowance = $this->make(Allowance::class);

        $this->decision
            ->setAllowance($allowance)
        ;

        $errors = $this->validator->validate($this->decision);
        $this->assertEquals(0, sizeof($errors));

        verify($this->decision->getBudget())->equals(0);
        verify($this->decision->getExpiredAt())->equals(null);
        verify($this->decision->getAllowance())->equals($allowance);
        //});
    }

    public function testCanSaveDecision()
    {
        /** @var Allowance $allowance */
        $allowance = $this->tester->have(Allowance::class, ['budget' => 100]);

        /** @var Decision $decision */
        $decision = $this->tester->have(Decision::class, ['allowance' => $allowance]);

        // verify data was saved using framework methods
        $this->tester->seeInRepository(Decision::class, [
            'budget' => $allowance->getBudget(),
            'createdAt' => $decision->getCreatedAt(),
            //'expiredAt' => new \DateTime('+1 years'),
            'allowance' => $allowance->getId(),
        ]);
    }
}
