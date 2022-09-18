<?php


namespace App\Tests\Unit;

use App\Entity\Parcel;
use App\Entity\Taxi;
use App\Tests\UnitTester;
use Codeception\Specify;
use Symfony\Component\Validator\Validator\TraceableValidator;

class TaxiTest extends \Codeception\Test\Unit
{
    use Specify;

    protected UnitTester $tester;
    private \Faker\Generator $faker;
    private TraceableValidator $validator;

    /**
     * @specify
     */
    private Taxi $taxi;

    protected function _before()
    {
        $this->faker = \Faker\Factory::create('nl_NL');
        $this->validator = $this->tester->grabService('validator');
    }

    // tests
    public function testCanMakeTaxi()
    {
        $this->taxi = new Taxi();

        //$this->specify("Taxi is ok", function() {

        /** @var Parcel $parcel */
        $parcel = $this->make(Parcel::class);

        $name = $this->faker->sentence();
        $this->taxi
            ->setName($name)
            ->setParcel($parcel)
        ;

        $errors = $this->validator->validate($this->taxi);
        $this->assertEquals(0, sizeof($errors));

        verify($this->taxi->getName())->equals($name);
        verify($this->taxi->getParcel())->equals($parcel);
        //});
    }

    public function testCanSaveTaxi()
    {
        /** @var Parcel $parcel */
        $parcel = $this->tester->have(Parcel::class);

        /** @var Taxi $taxi */
        $taxi = $this->tester->have(Taxi::class, ['parcel' => $parcel]);

        // verify data was saved using framework methods
        $this->tester->seeInRepository(Taxi::class, [
            'name' => $taxi->getName(),
            'parcel' => $parcel->getId()
        ]);
    }
}
