<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="idComm", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcomm;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomu", type="string", length=255, nullable=true)
     */
    private $nomu;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenomu", type="string", length=255, nullable=true)
     */
    private $prenomu;

    /**
     * @var string|null
     *
     * @ORM\Column(name="contenu", type="string", length=255, nullable=true)
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="commentaires" )
     */
    private $idclient;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePost;

    /**
     * @ORM\ManyToOne(targetEntity=Plan::class, inversedBy="commentaires")
     */
    private $plan;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaire::class, inversedBy="replies")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="idComm",onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="parent")
     */
    private $replies;

    /**
     * @ORM\OneToMany(targetEntity=CommentLike::class, mappedBy="comm")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=CommentDislike::class, mappedBy="comm")
     */
    private $dislikes;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
    }











    /**
     * @return int
     */
    public function getIdcomm(): int
    {
        return $this->idcomm;
    }

    /**
     * @param int $idcomm
     */
    public function setIdcomm(int $idcomm): void
    {
        $this->idcomm = $idcomm;
    }

    /**
     * @return string|null
     */
    public function getNomu(): ?string
    {
        return $this->nomu;
    }

    /**
     * @param string|null $nomu
     */
    public function setNomu(?string $nomu): void
    {
        $this->nomu = $nomu;
    }

    /**
     * @return string|null
     */
    public function getPrenomu(): ?string
    {
        return $this->prenomu;
    }

    /**
     * @param string|null $prenomu
     */
    public function setPrenomu(?string $prenomu): void
    {
        $this->prenomu = $prenomu;
    }

    /**
     * @return string|null
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    /**
     * @param string|null $contenu
     */
    public function setContenu(?string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function getIdclient(): ?Utilisateur
    {
        return $this->idclient;
    }

    public function setIdclient(?Utilisateur $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }

    public function getDatePost(): ?\DateTimeInterface
    {
        return $this-> datePost;
    }

    public function setDatePost(?\DateTimeInterface $datePost): self
    {
        $this->datePost = $datePost;

        return $this;
    }

    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public function setPlan(?Plan $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(self $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setParent($this);
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getParent() === $this) {
                $reply->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(CommentLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setComm($this);
        }

        return $this;
    }

    public function removeLike(CommentLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getComm() === $this) {
                $like->setComm(null);
            }
        }

        return $this;
    }

    /**
     * @param Utilisateur $utilisateur
     * @return bool
     */
public function isLikedByUser(Utilisateur $utilisateur):bool
{
    foreach ($this->likes as $like)
    {
        if($like->getUser()== $utilisateur) return true;
    }
    return false;
}



    /**
     * @param Utilisateur $utilisateur
     * @return bool
     */
    public function isDislikedByUser(Utilisateur $utilisateur):bool
    {
        foreach ($this->dislikes as $dislike)
        {
            if($dislike->getUser()== $utilisateur) return true;
        }
        return false;
    }
/**
 * @return Collection<int, CommentDislike>
 */
public function getDislikes(): Collection
{
    return $this->dislikes;
}

public function addDislike(CommentDislike $dislike): self
{
    if (!$this->dislikes->contains($dislike)) {
        $this->dislikes[] = $dislike;
        $dislike->setComm($this);
    }

    return $this;
}

public function removeDislike(CommentDislike $dislike): self
{
    if ($this->dislikes->removeElement($dislike)) {
        // set the owning side to null (unless already changed)
        if ($dislike->getComm() === $this) {
            $dislike->setComm(null);
        }
    }

    return $this;
}












}
