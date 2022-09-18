<?php

namespace App\Tests\Unit;

use App\Entity\Parcel;
use App\Tests\UnitTester;
use Codeception\Specify;
use Symfony\Component\Validator\Validator\TraceableValidator;

class ParcelTest extends \Codeception\Test\Unit
{
    use Specify;

    protected UnitTester $tester;
    private \Faker\Generator $faker;
    private TraceableValidator $validator;

    /**
     * @specify
     */
    private Parcel $parcel;

    protected function _before()
    {
        $this->faker = \Faker\Factory::create('nl_NL');
        $this->validator = $this->tester->grabService('validator');
    }

    // tests
    public function testCanMakeParcel()
    {
        $this->parcel = new Parcel();

        //$this->specify("Parcel is ok", function() {
            $name = $this->faker->sentence();
            $this->parcel->setName($name);

            $errors = $this->validator->validate($this->parcel);
            $this->assertEquals(0, sizeof($errors));

            verify($this->parcel->getName())->equals($name);
        //});
    }

    public function testCanSave()
    {
        /** @var Parcel $parcel */
        $parcel = $this->tester->have(Parcel::class);

        // verify data was saved using framework methods
        $this->tester->seeInRepository(Parcel::class, [
            'name' => $parcel->getName()
        ]);
    }
}
