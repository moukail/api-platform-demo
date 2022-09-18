<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Allowance;
use App\Entity\AllowanceStatus;
use App\Entity\Decision;
use App\Entity\Parcel;
use App\Entity\Resident;
use App\Entity\Taxi;
use Doctrine\Persistence\ObjectManager;

class ParcelFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Parcel::class, 5, function (Parcel $parcel, $count) use ($manager) {
            $parcel
                ->setName($this->faker->name())
            ;

            $this->generateResident($manager, $parcel, 5);
            $this->generateTaxi($manager, $parcel, 3);

        });

        $manager->flush();
    }

    /**
     * @param Parcel $parcel
     * @return void
     */
    function generateTaxi(ObjectManager $manager, Parcel $parcel, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $taxi = new Taxi();
            $taxi
                ->setName($this->faker->name())
                ->setParcel($parcel);

            $manager->persist($taxi);
        }
    }

    /**
     * @param Parcel $parcel
     * @return void
     */
    function generateResident(ObjectManager $manager, Parcel $parcel, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $address = new Address();
            $address
                ->setCity('Utrecht')
                ->setStreet($this->faker->streetName())
                ->setPostalCode($this->faker->postcode())
                ->setHouseNumber((int) $this->faker->buildingNumber());

            $manager->persist($address);

            $resident = new Resident();
            $resident
                ->setName($this->faker->name())
                ->setAddress($address)
                ->setParcel($parcel);

            $manager->persist($resident);

            $allowence = new Allowance();
            $allowence
                ->setBudget(100)
                ->setResident($resident)
                ->setStatus(AllowanceStatus::ACTIVE->value)
            ;

            $manager->persist($allowence);

            $decision = new Decision();
            $decision
                ->setAllowance($allowence)
            ;

            $manager->persist($decision);
        }
    }
}
