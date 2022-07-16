<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Facture
 *
 * @ORM\Table(name="facture", indexes={@ORM\Index(name="id_client", columns={"idClient"})})
 * @ORM\Entity(repositoryClass="App\Repository\FactureRepository")
 */

/*, indexes={@ORM\Index(name="id_client", columns={"idClient"})}*/
class Facture
{
    /**
     * @var int
     *
     * @ORM\Column(name="idFacture", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfacture;

    /**
     * @var float
     * @Assert\Positive
     * @ORM\Column(name="prix_Total", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixTotal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idClient", referencedColumnName="id")
     * })
     */
    private Utilisateur $idclient;



    public function getIdfacture(): ?int
    {
        return $this->idfacture;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Utilisateur
     */

    public function getIdclient(): Utilisateur
    {
        return $this->idclient;
    }

    public function setIdclient(Utilisateur $idclient):  void //self
    {
        $this->idclient = $idclient;

        //return $this;
    }

    /**
     * @var Reservation
     *
     * @ORM\OneToOne(targetEntity="Reservation",fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="numReservation", referencedColumnName="numReservation")
     * })
     */

    private Reservation $numReservation ;


    /**
     * @return Reservation
     */
    public function getNumreservation():Reservation
    {
        return $this->numReservation;
    }

    /**
     * @param Reservation $reservation
     */

    public function setNumReservation(Reservation $reservation): void
    {
         $this->numReservation=$reservation;
    }


    public function __construct()
    {
        //$this->numReservation = new ArrayCollection();
    }


    public function addReservation(Reservation $reservation): self
    {
        if (!$this->numReservation->contains($reservation)) {
            $this->numReservation[] = $reservation;
            $reservation->setNumreservation($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->numReservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getFacture() === $this) {
                $reservation->setFacture(null);
            }
        }

        return $this;
    }











































}
