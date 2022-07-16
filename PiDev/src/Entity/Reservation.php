<?php

namespace App\Entity;

use DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Plan;
use App\Entity\Utilisateur;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="clientFacture", columns={"idClient"}), @ORM\Index(name="id_plan", columns={"idPlan"})})
 * @ORM\Entity (repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @return int
     */
    public function getNumreservation(): ?int
    {
        return $this->numreservation;
    }

    /**
     * @param int|null $nbrplace
     */
    public function setNbrplace(?int $nbrplace): void
    {
        $this->nbrplace = $nbrplace;
    }

    /**
     * @param DateTime|null $datedebut
     */
    public function setDatedebut($datedebut): void
    {
        $this->datedebut = $datedebut;
    }

    /**
     * @param DateTime|null $datefin
     */
    public function setDatefin($datefin): void
    {
        $this->datefin = $datefin;
    }

    /**
     * @return int|null
     */
    public function getNbrplace(): ?int
    {
        return $this->nbrplace;
    }

    /**
     * @return DateTime|null
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     *
     * @return DateTime|null
     */
    public function getDatefin()
    {
        return $this->datefin;
    }





    /**
     * @return Utilisateur
     */
    public function getIdclient(): Utilisateur
    {
        return $this->idclient;
    }

    /**
     * @param int $numreservation
     */
    public function setNumreservation(int $numreservation): void
    {
        $this->numreservation = $numreservation;
    }

    /**
     * @param \App\Entity\Utilisateur $idclient
     */
    public function setIdclient(\App\Entity\Utilisateur $idclient): void
    {
        $this->idclient = $idclient;
    }


    /**
     * @var int
     *
     * @ORM\Column(name="numReservation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups ("post:read")
     *
     */
    private $numreservation;

    /**
     * @var int
     *
     * @Assert\Positive
     *
     * @Assert\Range(min=1,max=10)
     *
     * )
     * @ORM\Column(name="nbrPlace", type="integer", nullable=false, options={"default"="NULL"})
     *
     * @Groups ("post:read")
     *
     */
    private $nbrplace = NULL;

    /**
     * @var DateType|date
     *
     * @Assert\Expression ("this.getDatedebut() <= this.getDatefin()",
     *     message="Start date should be less than  end date!"
     *)
     * @ORM\Column(name="dateDebut", type="date", nullable=true,options={"default"="NULL"})
     *
     * @Groups ("post:read")
     *
     */
    private $datedebut ;

    /**
     * @var DateType|date
     * @Assert\Expression("this.getDatefin() >= this.getDatedebut()",
     *   message="date fin doit etre superieur à la date de debut ! ")
     *
     *
     * @ORM\Column(name="dateFin", type="date", nullable=true, options={"default"="NULL"})
     *
     * @Groups ("post:read")
     *
     */
    private $datefin ;

    /**
     * @var Plan
     *
     * @ORM\ManyToOne(targetEntity="Plan", inversedBy="reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPlan", referencedColumnName="id")
     * })
     *
     *@Groups ("post:read")
     *
     */
    private Plan $plan ;

    /**
     * @return Plan
     */

    public function getPlan(): Plan
    {
        return $this->plan;
    }

//    /**
//     * @param \Plan $plan
//     * @return ?Plan
//     */

    public function setPlan(?Plan $plan): self
    {
        $this->plan = $plan;
        return $this;
    }




    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idClient", referencedColumnName="id")
     * })
     *
     * @Groups ("post:read")
     *
     */
    private Utilisateur $idclient;
    //private $utilisateur;

    protected $captchaCode;


    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }


    public function setCaptchaCode($captchaCode): void
    {
        $this->captchaCode = $captchaCode;
    }

    /* /**
      * @var Facture
      * @ORM\ManyToOne(targetEntity="Facture")
      ** @ORM\JoinColumns({
      *   @ORM\JoinColumn(name="idFcture", referencedColumnName="idFacture")
      * })
      */

    /*
        private  Facture $facture;

        /**
         * @return Facture
         */
    /*  public function getFacture(): Facture
      {
          return $this->facture;
      }

      /**
       * @param Facture $facture
       */
    /*public function setFacture(Facture $facture): void
    {
        $this->facture = $facture;
    }*/


    public function __toString()
    {
        // TODO: Implement __toString() method.
        //   return $this->plan->getTitre();

    }


    public function __construct()
    {

    }

















}
