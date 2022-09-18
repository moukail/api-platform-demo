<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'addresses')]
#[ORM\UniqueConstraint(name: "address_unique", columns:["postal_code", "house_number", "addition"])]
#[ApiResource(
    normalizationContext: ['groups' => ['address:read']],
    denormalizationContext: ['groups' => ['address:write']]
)]
class Address
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    #[Groups(['address:read', 'ride:write'])]
    private int $id;

    #[ORM\Column(length:4)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 4)]
    #[Groups(['address:read', 'address:write', 'ride:write', 'ride:read', 'resident:read'])]
    private int $houseNumber;

    #[ORM\Column(length:2, nullable:true)]
    #[Assert\Length(max: 2)]
    #[Groups(['address:read', 'address:write', 'ride:write', 'ride:read', 'resident:read'])]
    private ?string $addition = null;

    #[ORM\Column(length:6)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 6)]
    #[Groups(['address:read', 'address:write', 'ride:write', 'ride:read', 'resident:read'])]
    private string $postalCode;

    #[ORM\Column(nullable:true)]
    #[Groups(['address:read', 'address:write', 'ride:write', 'ride:read', 'resident:read'])]
    private ?string $street = null;

    #[ORM\Column(nullable:true)]
    #[Groups(['address:read', 'address:write', 'ride:write', 'ride:read', 'resident:read'])]
    private ?string $city = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getHouseNumber(): ?int
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(int $houseNumber): self
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    public function getAddition(): ?string
    {
        return $this->addition;
    }

    public function setAddition(?string $addition): self
    {
        $this->addition = $addition;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }
}
