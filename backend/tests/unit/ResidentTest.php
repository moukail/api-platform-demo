<?php

namespace App\Tests\Unit;

use App\Entity\Address;
use App\Entity\Parcel;
use App\Entity\Resident;
use App\Tests\UnitTester;
use Codeception\Specify;
use Symfony\Component\Validator\Validator\TraceableValidator;

class ResidentTest extends \Codeception\Test\Unit
{
    use Specify;

    protected UnitTester $tester;
    private \Faker\Generator $faker;
    private TraceableValidator $validator;

    /**
     * @specify
     */
    private Resident $resident;

    protected function _before()
    {
        $this->faker = \Faker\Factory::create('nl_NL');
        $this->validator = $this->tester->grabService('validator');
    }

    // tests
    public function testCanMakeResident()
    {
        $this->resident = new Resident();

        //$this->specify("Resident is ok", function() {

        /** @var Parcel $parcel */
        $parcel = $this->make(Parcel::class);

        /** @var Address $address */
        $address = $this->make(Address::class);

        $name = $this->faker->name();
        $this->resident
            ->setName($name)
            ->setParcel($parcel)
            ->setAddress($address)
        ;

        $errors = $this->validator->validate($this->resident);
        $this->assertEquals(0, sizeof($errors));

        verify($this->resident->getName())->equals($name);
        verify($this->resident->getParcel())->equals($parcel);
        verify($this->resident->getAddress())->equals($address);
        //});
    }

    public function testCanSaveResident()
    {
        /** @var Parcel $parcel */
        $parcel = $this->tester->have(Parcel::class);

        /** @var Address $address */
        $address = $this->tester->have(Address::class);

        /** @var Resident $resident */
        $resident = $this->tester->have(Resident::class, ['parcel' => $parcel, 'address' => $address]);

        // verify data was saved using framework methods
        $this->tester->seeInRepository(Resident::class, [
            'name' => $resident->getName(),
            'parcel' => $parcel->getId(),
            'address' => $address->getId(),
        ]);
    }
}
