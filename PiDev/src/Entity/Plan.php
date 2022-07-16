<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Plan
 *
 * @ORM\Table(name="plan", indexes={@ORM\Index(name="idguide_fk", columns={"idGuide"})})
 * @ORM\Entity
 */
class Plan
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
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private float $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=300, nullable=false)
     *
     */
    private string $titre;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=5000, nullable=false)
     *
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="nmbrPlacesMax", type="integer", nullable=false)
     */
    private int $nmbrplacesmax;

    /**
     * @var int
     *
     * @ORM\Column(name="nmbrPlacesReste", type="integer", nullable=false)
     */
    private int $nmbrplacesreste;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=false)
     */
    private DateTime $datedebut;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=false)
     */
    private DateTime $datefin;

    /**
     * @var Place
     *
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pointDepart", referencedColumnName="id")
     * })
     */
    private Place $pointdepart;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float", precision=10, scale=0, nullable=false)
     */
    private float $note;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idGuide", referencedColumnName="id")
     * })
     */
    private Utilisateur $idguide;



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
     * @return float
     */
    public function getPrix(): float
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getNmbrplacesmax(): int
    {
        return $this->nmbrplacesmax;
    }

    /**
     * @param int $nmbrplacesmax
     */
    public function setNmbrplacesmax(int $nmbrplacesmax): void
    {
        $this->nmbrplacesmax = $nmbrplacesmax;
    }

    /**
     * @return int
     */
    public function getNmbrplacesreste(): int
    {
        return $this->nmbrplacesreste;
    }

    /**
     * @param int $nmbrplacesreste
     */
    public function setNmbrplacesreste(int $nmbrplacesreste): void
    {
        $this->nmbrplacesreste = $nmbrplacesreste;
    }

    /**
     * @return DateTime
     */
    public function getDatedebut(): DateTime
    {
        return $this->datedebut;
    }

    /**
     * @param DateTime $datedebut
     */
    public function setDatedebut(DateTime $datedebut): void
    {

        $this->datedebut = $datedebut;
    }

    /**
     * @return DateTime
     */
    public function getDatefin(): DateTime
    {
        return $this->datefin;
    }

    /**
     * @param DateTime $datefin
     */
    public function setDatefin(DateTime $datefin): void
    {
        $this->datefin = $datefin;
    }

    /**
     * @return Place
     */
    public function getPointdepart(): Place
    {
        return $this->pointdepart;
    }

    /**
     * @param string $pointdepart
     */
    public function setPointdepart(Place $pointdepart): void
    {
        $this->pointdepart = $pointdepart;
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
     * @return Utilisateur
     */
    public function getIdguide(): Utilisateur
    {
        return $this->idguide;
    }

    /**
     * @param Utilisateur $idguide
     */
    public function setIdguide(Utilisateur $idguide): void
    {
        $this->idguide = $idguide;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getTitre();
    }
    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="plan")
     */
    private $commentaires;
/*
    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getCommentaire(): ?Commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?Commentaire $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }*/

/*z  /**
     * @return Collection<int, Commentaire>
     */
  /*  public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }*/

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setPlan($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPlan() === $this) {
                $commentaire->setPlan(null);
            }
        }

        return $this;
    }

}
