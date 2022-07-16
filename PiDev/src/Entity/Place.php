<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Place
 *
 * @ORM\Table(name="place")
 * @ORM\Entity
 */
class Place
{
    const __SESSION__ = "GUIDE";
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=200, nullable=false)
     */
    private string $nom;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float", precision=10, scale=0, nullable=false)
     */
    private float $note;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=200, nullable=false)
     */
    private string $address;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    private string $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=200, nullable=false)
     */
    private string $type;

    /**
     * @ORM\OneToMany(targetEntity=PlaceImage::class, mappedBy="idplace")
     */
    private $placeImages;

    public function __construct()
    {
        $this->placeImages = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return float
     */
    public function getNote(): float
    {
        return $this->note;
    }

    /**
     * @param float $note
     */
    public function setNote(float $note): void
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getNom();
    }

    /**
     * @return Collection<int, PlaceImage>
     */
    public function getPlaceImages(): Collection
    {
        return $this->placeImages;
    }

    public function addPlaceImage(PlaceImage $placeImage): self
    {
        if (!$this->placeImages->contains($placeImage)) {
            $this->placeImages[] = $placeImage;
            $placeImage->setIdplace($this);
        }

        return $this;
    }

    public function removePlaceImage(PlaceImage $placeImage): self
    {
        if ($this->placeImages->removeElement($placeImage)) {
            // set the owning side to null (unless already changed)
            if ($placeImage->getIdplace() === $this) {
                $placeImage->setIdplace(null);
            }
        }

        return $this;
    }


}
