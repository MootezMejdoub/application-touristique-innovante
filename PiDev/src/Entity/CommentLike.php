<?php

namespace App\Entity;

use App\Repository\CommentLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentLikeRepository::class)
 */
class CommentLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaire::class, inversedBy="likes")
     * @ORM\JoinColumn(name="comm_id", referencedColumnName="idComm",onDelete="CASCADE")
     */
    private $comm;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="likes")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComm(): ?Commentaire
    {
        return $this->comm;
    }

    public function setComm(?Commentaire $comm): self
    {
        $this->comm = $comm;

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }
}
