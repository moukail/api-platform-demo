<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DecisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: DecisionRepository::class)]
#[ORM\UniqueConstraint(name: "decision_unique", columns:["allowance_id", "expired_at"])]
#[ApiResource(
    normalizationContext: ['groups' => ['decision:read']],
    denormalizationContext: ['groups' => ['decision:write']],
)]
class Decision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['decision:read', 'decision:write', 'ride:read', 'ride:write'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['decision:read'])]
    private ?float $budget = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Allowance $allowance = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTime $expiredAt = null;

    #[ORM\OneToMany(mappedBy: 'resident', targetEntity: Ride::class, orphanRemoval: true)]
    private Collection $rides;

    public function __construct()
    {
        $this->rides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getAllowance(): ?Allowance
    {
        return $this->allowance;
    }

    public function setAllowance(?Allowance $allowance): self
    {
        $this->allowance = $allowance;

        return $this;
    }

    public function getResident(): Resident
    {
        return $this->allowance->getResident();
    }

    public function getParcel(): Parcel
    {
        return $this->getResident()->getParcel();
    }

    /**
     * @return Collection<int, Ride>
     */
    public function getRides(): Collection
    {
        return $this->rides;
    }

    public function addRide(Ride $ride): self
    {
        if (!$this->rides->contains($ride)) {
            $this->rides->add($ride);
            $ride->setDecision($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): self
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getDecision() === $this) {
                $ride->setDecision(null);
            }
        }

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

    public function getExpiredAt(): ?\DateTime
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTime $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setExpiredAt(new \DateTime('+1 years'));
        $budget = $this->allowance->getBudget();
        $this->setBudget($budget);
    }
}
