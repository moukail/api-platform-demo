<?php

namespace App\Tests\Unit;

use App\Entity\Allowance;
use App\Entity\AllowanceStatus;
use App\Entity\Resident;
use App\Tests\UnitTester;
use Codeception\Specify;
use Symfony\Component\Validator\Validator\TraceableValidator;

class AllowanceTest extends \Codeception\Test\Unit
{
    use Specify;

    protected UnitTester $tester;
    private \Faker\Generator $faker;
    private TraceableValidator $validator;

    /**
     * @specify
     */
    private Allowance $allowance;

    protected function _before()
    {
        $this->faker = \Faker\Factory::create('nl_NL');
        $this->validator = $this->tester->grabService('validator');
    }

    // tests
    public function testCanMakeAllowance()
    {
        $this->allowance = new Allowance();

        //$this->specify("Resident is ok", function() {

        /** @var Resident $resident */
        $resident = $this->make(Resident::class);

        $this->allowance
            ->setBudget(100)
            ->setStatus(AllowanceStatus::PENDING->value)
            ->setResident($resident)
        ;

        $errors = $this->validator->validate($this->allowance);
        $this->assertEquals(0, sizeof($errors));

        verify($this->allowance->getBudget())->equals(100);
        verify($this->allowance->getStatus())->equals(AllowanceStatus::PENDING->value);
        verify($this->allowance->getResident())->equals($resident);
        //});
    }

    public function testCanSaveAllowance()
    {
        /** @var Resident $resident */
        $resident = $this->tester->have(Resident::class);

        /** @var Allowance $allowance */
        $allowance = $this->tester->have(Allowance::class, ['resident' => $resident]);

        // verify data was saved using framework methods
        $this->tester->seeInRepository(Allowance::class, [
            'budget' => $allowance->getBudget(),
            'status' => $allowance->getStatus(),
            'resident' => $resident->getId(),
        ]);
    }
}
