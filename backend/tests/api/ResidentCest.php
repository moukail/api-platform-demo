<?php

namespace App\Tests\Api;

use App\Entity\Resident;
use App\Tests\ApiTester;
use Codeception\Scenario;
use Codeception\Util\HttpCode;
use Faker\Generator;

class ResidentCest
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
    public function testCanGetResidents(ApiTester $I, Scenario $scenario)
    {
        $name = $this->faker->name();

        /** @var Resident $resident */
        $resident = $I->have(Resident::class, [
            'name'          => $name,
        ]);

        $I->sendGET('/residents.json');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();

        $I->seeResponseJsonMatchesJsonPath('$[*].id');
        $I->seeResponseJsonMatchesJsonPath('$[*].name');

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'address' => [
                'houseNumber' => 'integer',
                //'addition' => 'string|null',
                'postalCode' => 'string',
                'street' => 'string|null',
                'city' => 'string|null',
            ],
        ]);

        $I->seeResponseContainsJson([
            'name' => $name,
        ]);
    }
}