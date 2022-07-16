<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dating
 *
 * @ORM\Table(name="dating", indexes={@ORM\Index(name="match_date", columns={"id_match"})})
 * @ORM\Entity
 */
class Dating
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_Date", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDate;

    /**
     * @var int
     *
     * @ORM\Column(name="discount", type="integer", nullable=false)
     */
    private $discount;

    /**
     * @var \Matching
     *
     * @ORM\ManyToOne(targetEntity="Matching")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_match", referencedColumnName="id_match")
     * })
     */
    private $idMatch;

    public function getIdDate(): ?int
    {
        return $this->idDate;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getIdMatch(): ?Matching
    {
        return $this->idMatch;
    }

    public function setIdMatch(?Matching $idMatch): self
    {
        $this->idMatch = $idMatch;

        return $this;
    }


}
