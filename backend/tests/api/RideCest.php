<?php

namespace App\Tests\Api;

use App\Entity\Address;
use App\Entity\Decision;
use App\Entity\Resident;
use App\Entity\Ride;
use App\Entity\Taxi;
use App\Tests\ApiTester;
use Codeception\Scenario;
use Codeception\Util\HttpCode;
use Faker\Generator;

class RideCest
{
    private Generator $faker;

    public function beforeSuite(ApiTester $I)
    {

    }

    public function _before(ApiTester $I)
    {
        $this->faker = \Faker\Factory::create('nl_NL');
    }

    public function _after(ApiTester $I)
    {

    }

    /**
     * @param ApiTester $I
     * @param Scenario $scenario
     * @throws \Exception
     * @group student
     */
    public function testCanMakeRide(ApiTester $I, Scenario $scenario)
    {
        $distance = 3.8;

        /** @var Decision $decision */
        $decision = $I->have(Decision::class);

        /** @var Address $destination */
        //$destination = $I->have(Address::class);

        $houseNumber = $this->faker->buildingNumber();
        $postalCode = $this->faker->postcode();

        /** @var Taxi $taxi */
        $taxi = $I->have(Taxi::class, ['parcel' => $decision->getParcel()]);

        $location = $decision->getResident()->getAddress();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/rides', [
            'decision' => ['id' => $decision->getId()],
            'taxi' => ['id' => $taxi->getId()],
            'location' => [
                'id' => $location->getId(),
            ],
            'destination' => [
                'houseNumber' => (int) $houseNumber,
                'postalCode' => $postalCode,
            ],
            'distance' => $distance,
        ]);

        //$I->seeHttpHeader('content-type', 'application/json');
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();

        $I->seeInRepository(Address::class, [
            'houseNumber' => $houseNumber,
            'postalCode' => $postalCode,
        ]);

        $I->seeInRepository(Address::class, [
            'houseNumber' => $houseNumber,
            'postalCode' => $postalCode,
        ]);

        $destination = $I->grabEntityFromRepository(Address::class, [
            'houseNumber' => $houseNumber,
            'postalCode' => $postalCode,
        ]);

        $I->seeInRepository(Ride::class, [
            'decision' => $decision,
            'taxi' => $taxi,
            'location' => $decision->getResident()->getAddress(),
            'destination' => $destination,
            'distance' => $distance,
        ]);
    }

    public function testCantMakeRideWithLowBudget(ApiTester $I, Scenario $scenario)
    {
        $distance = 120;

        /** @var Decision $decision */
        $decision = $I->have(Decision::class, ['budget' => 10]);

        /** @var Address $destination */
        $destination = $I->have(Address::class);

        /** @var Taxi $taxi */
        $taxi = $I->have(Taxi::class, ['parcel' => $decision->getParcel()]);

        $location = $decision->getResident()->getAddress();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/rides', [
            'decision' => ['id' => $decision->getId()],
            'taxi' => ['id' => $taxi->getId()],
            'location' => [
                'id' => $location->getId(),
            ],
            'destination' => [
                'id' => $destination->getId(),
            ],
            'distance' => $distance,
        ]);

        //$I->seeHttpHeader('content-type', 'application/json');
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->dontSeeInRepository(Ride::class, [
            'decision' => $decision,
            'taxi' => $taxi,
            'location' => $decision->getResident()->getAddress(),
            'destination' => $destination,
            'distance' => $distance,
        ]);
    }

    public function testCantMakeRideWithDifferentParcel(ApiTester $I, Scenario $scenario)
    {
        $distance = 10;

        /** @var Decision $decision */
        $decision = $I->have(Decision::class);

        /** @var Address $destination */
        $destination = $I->have(Address::class);

        /** @var Taxi $taxi */
        $taxi = $I->have(Taxi::class);

        $location = $decision->getResident()->getAddress();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/rides', [
            'decision' => ['id' => $decision->getId()],
            'taxi' => ['id' => $taxi->getId()],
            'location' => [
                'id' => $location->getId(),
            ],
            'destination' => [
                'id' => $destination->getId(),
            ],
            'distance' => $distance,
        ]);

        //$I->seeHttpHeader('content-type', 'application/json');
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->dontSeeInRepository(Ride::class, [
            'decision' => $decision,
            'taxi' => $taxi,
            'location' => $decision->getResident()->getAddress(),
            'destination' => $destination,
            'distance' => $distance,
        ]);
    }

    public function testTaxiCanGetOwnRides(ApiTester $I, Scenario $scenario)
    {
        $name = $this->faker->name();

        /** @var Taxi $taxi */
        $taxi = $I->have(Taxi::class, [
            'name' => $name,
        ]);

        /** @var Ride $ride */
        $ride = $I->have(Ride::class, ['taxi' => $taxi]);

        $I->sendGET('/rides?taxi=' . $taxi->getId());

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();

        $I->seeResponseJsonMatchesJsonPath('$[*].id');
        $I->seeResponseJsonMatchesJsonPath('$[*].distance');

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'distance' => 'float',
            'location' => [
                'houseNumber' => 'integer',
                //'addition' => 'string|null',
                'postalCode' => 'string',
                'street' => 'string|null',
                'city' => 'string|null',
            ],
            'destination' => [
                'houseNumber' => 'integer',
                //'addition' => 'string|null',
                'postalCode' => 'string',
                'street' => 'string|null',
                'city' => 'string|null',
            ],
        ]);

        $I->seeResponseContainsJson([
            'distance' => $ride->getDistance(),
        ]);
    }
}