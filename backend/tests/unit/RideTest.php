<?php


namespace App\Tests\Unit;

use App\Entity\Address;
use App\Entity\Allowance;
use App\Entity\Decision;
use App\Entity\Parcel;
use App\Entity\Resident;
use App\Entity\Ride;
use App\Entity\Taxi;
use App\Tests\UnitTester;
use Codeception\Specify;
use Symfony\Component\Validator\Validator\TraceableValidator;

class RideTest extends \Codeception\Test\Unit
{
    use Specify;

    protected UnitTester $tester;
    private \Faker\Generator $faker;
    private TraceableValidator $validator;

    /**
     * @specify
     */
    private Ride $ride;

    protected function _before()
    {
        $this->faker = \Faker\Factory::create('nl_NL');
        $this->validator = $this->tester->grabService('validator');
    }

    // tests
    public function testCanMakeRide()
    {
        $this->ride = new Ride();

        //$this->specify("Resident is ok", function() {

        /** @var Address $location */
        $location = $this->make(Address::class);

        /** @var Address $destination */
        $destination = $this->make(Address::class);

        /** @var Parcel $parcel */
        $parcel = $this->make(Parcel::class);

        /** @var Resident $resident */
        $resident = $this->make(Resident::class, ['parcel' => $parcel]);

        /** @var Allowance $allowance */
        $allowance = $this->make(Allowance::class, ['resident' => $resident]);

        /** @var Decision $decision */
        $decision = $this->make(Decision::class, ['allowance' => $allowance, 'budget' => 100]);

        /** @var Taxi $taxi */
        $taxi = $this->make(Taxi::class, ['parcel' => $parcel]);

        $this->ride
            ->setLocation($location)
            ->setDestination($destination)
            ->setDistance(1.6)
            ->setDecision($decision)
            ->setTaxi($taxi)
        ;

        $errors = $this->validator->validate($this->ride);
        $this->assertEquals(0, sizeof($errors));

        verify($this->ride->getDistance())->equals(1.6);
        verify($this->ride->getLocation())->equals($location);
        verify($this->ride->getDestination())->equals($destination);
        verify($this->ride->getTaxi())->equals($taxi);
        verify($this->ride->getDecision())->equals($decision);
        //});
    }

    public function testCantMakeRide()
    {
        // todo test can't make ride with low budget
    }

    public function testCanSaveRide()
    {
        /** @var Taxi $taxi */
        $taxi = $this->tester->have(Taxi::class);

        /** @var Decision $decision */
        $decision = $this->tester->have(Decision::class);

        /** @var Ride $ride */
        $ride = $this->tester->have(Ride::class, ['decision' => $decision, 'taxi' => $taxi]);

        // verify data was saved using framework methods
        $this->tester->seeInRepository(Ride::class, [
            'location' => $ride->getLocation()->getId(),
            'destination' => $ride->getDestination()->getId(),
            'distance' => $ride->getDistance(),
            'taxi' => $taxi->getId(),
            'decision' => $decision->getId(),
        ]);
    }
}
