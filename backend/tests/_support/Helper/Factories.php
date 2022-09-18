<?php

namespace App\Tests\Helper;

use App\Entity\Address;
use App\Entity\Allowance;
use App\Entity\AllowanceStatus;
use App\Entity\Decision;
use App\Entity\Parcel;
use App\Entity\Resident;
use App\Entity\Ride;
use App\Entity\Taxi;
use League\FactoryMuffin\Faker\Facade as Faker;

class Factories extends \Codeception\Module
{
    public function _beforeSuite($settings = array())
    {
        $factory = $this->getModule('DataFactory');
        $faker = \Faker\Factory::create('nl_NL'); // create a dutch faker

        Faker::instance()->setLocale('nl_NL');

        $factory->_define(Address::class, [
            'houseNumber'   => (int) $faker->buildingNumber(),
            'addition'   => Faker::toUpper($faker->randomLetter()),
            'postalCode' => Faker::postcode(),
            'street' => Faker::streetName(),
            'city' => Faker::city(),
        ]);

        $factory->_define(Parcel::class, [
            'name' => Faker::sentence()
        ]);

        $factory->_define(Taxi::class, [
            'name' => Faker::sentence(),
            'parcel' => 'entity|' . Parcel::class,
        ]);

        $factory->_define(Resident::class, [
            'name' => Faker::name(),
            'parcel' => 'entity|' . Parcel::class,
            'address' => 'entity|' . Address::class,
        ]);

        $factory->_define(Allowance::class, [
            'budget' => 100,
            'status' => AllowanceStatus::PENDING->value,
            'resident' => 'entity|' . Resident::class,
        ]);

        $factory->_define(Decision::class, [
            'allowance' => 'entity|' . Allowance::class,
        ]);

        $factory->_define(Ride::class, [
            'distance' => 1.7,
            'location' => 'entity|' . Address::class,
            'destination' => 'entity|' . Address::class,
            'decision' => 'entity|' . Decision::class,
            'taxi' => 'entity|' . Taxi::class,
        ]);
    }
}