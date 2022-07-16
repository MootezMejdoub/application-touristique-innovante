<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse
 *
 * @ORM\Table(name="reponse", indexes={@ORM\Index(name="rec_reference", columns={"rec_reference"}), @ORM\Index(name="user_id", columns={"rec_id"}), @ORM\Index(name="recid", columns={"rec_id"})})
 * @ORM\Entity
 */
class Reponse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datecreation", type="date", nullable=true, options={"default"="NULL"})
     */
    private $datecreation ;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $message ;

    /**
     * @var \Reclamation
     *
     * @ORM\ManyToOne(targetEntity="Reclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rec_reference", referencedColumnName="rec_reference")
     * })
     */
    private $recReference;

    /**
     * @var \Reclamation
     *
     * @ORM\ManyToOne(targetEntity="Reclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rec_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $rec;

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
     * @return \DateTime|null
     */
    public function getDatecreation(): ?\DateTime
    {
        return $this->datecreation;
    }

    /**
     * @param \DateTime|null $datecreation
     */
    public function setDatecreation(?\DateTime $datecreation): void
    {
        $this->datecreation = $datecreation;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return \Reclamation
     */
    public function getRecReference(): \Reclamation
    {
        return $this->recReference;
    }

    /**
     * @param \Reclamation $recReference
     */
    public function setRecReference(\Reclamation $recReference): void
    {
        $this->recReference = $recReference;
    }

    public function getRec(): ?Reclamation
    {
        return $this->rec;
    }

    public function setRec(?Reclamation $rec): self
    {
        $this->rec = $rec;

        return $this;
    }

}
