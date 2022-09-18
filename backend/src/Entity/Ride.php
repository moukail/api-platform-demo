<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\RideRepository;
use App\Validator\BudgetConstraint;
use App\Validator\ParcelConstraint;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RideRepository::class)]
#[BudgetConstraint]
#[ParcelConstraint]
#[ApiResource(
    normalizationContext: ['groups' => ['ride:read']],
    denormalizationContext: ['groups' => ['ride:write']],
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'taxi' => 'exact'])]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ride:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ride:read', 'ride:write'])]
    private ?Decision $decision = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ride:read', 'ride:write'])]
    private ?Taxi $taxi = null;

    #[ORM\ManyToOne(cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ride:read', 'ride:write'])]
    private ?Address $location = null;

    #[ORM\ManyToOne(cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ride:read', 'ride:write'])]
    private ?Address $destination = null;

    #[ORM\Column]
    #[Groups(['ride:read', 'ride:write'])]
    private ?float $distance = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Decision|null
     */
    public function getDecision(): ?Decision
    {
        return $this->decision;
    }

    /**
     * @param Decision|null $decision
     */
    public function setDecision(?Decision $decision): self
    {
        $this->decision = $decision;

        return $this;
    }

    public function getTaxi(): ?Taxi
    {
        return $this->taxi;
    }

    public function setTaxi(?Taxi $taxi): self
    {
        $this->taxi = $taxi;

        return $this;
    }

    public function getLocation(): ?Address
    {
        return $this->location;
    }

    public function setLocation(Address $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDestination(): ?Address
    {
        return $this->destination;
    }

    public function setDestination(Address $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable|null $updatedAt
     */
    private function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTimeImmutable());
    }
}
