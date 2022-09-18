<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ParcelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ParcelRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['parcel:read']],
    denormalizationContext: ['groups' => ['parcel:write']]
)]
class Parcel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['parcel:read', 'ride:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['parcel:read', 'parcel:write'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'parcel', targetEntity: Taxi::class)]
    private Collection $taxis;

    public function __construct()
    {
        $this->taxis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Taxi>
     */
    public function getTaxis(): Collection
    {
        return $this->taxis;
    }

    public function addTaxi(Taxi $taxi): self
    {
        if (!$this->taxis->contains($taxi)) {
            $this->taxis->add($taxi);
            $taxi->setParcel($this);
        }

        return $this;
    }

    public function removeTaxi(Taxi $taxi): self
    {
        if ($this->taxis->removeElement($taxi)) {
            // set the owning side to null (unless already changed)
            if ($taxi->getParcel() === $this) {
                $taxi->setParcel(null);
            }
        }

        return $this;
    }
}
