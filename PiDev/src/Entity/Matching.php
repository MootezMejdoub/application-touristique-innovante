<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matching
 *
 * @ORM\Table(name="matching", indexes={@ORM\Index(name="Client2", columns={"client2"}), @ORM\Index(name="Client1", columns={"client1"})})
 * @ORM\Entity
 */
class Matching
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_match", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMatch;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client1", referencedColumnName="id")
     * })
     */
    private $client1;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client2", referencedColumnName="id")
     * })
     */
    private $client2;

    public function getIdMatch(): ?int
    {
        return $this->idMatch;
    }

    public function getClient1(): ?Utilisateur
    {
        return $this->client1;
    }

    public function setClient1(?Utilisateur $client1): self
    {
        $this->client1 = $client1;

        return $this;
    }

    public function getClient2(): ?Utilisateur
    {
        return $this->client2;
    }

    public function setClient2(?Utilisateur $client2): self
    {
        $this->client2 = $client2;

        return $this;
    }


}
