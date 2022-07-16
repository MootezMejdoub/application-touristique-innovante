<?php

namespace App\Entity;
use http\Env\Request;
use http\Env\Response;
use JetBrains\PhpStorm\Internal\TentativeType;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Mapping\ClassMetadata;



use Symfony\Component\Validator\Constraints as Assert;
/**
 * Utilisateur
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @ORM\Table(name="utilisateur", indexes={@ORM\Index(name="prenom", columns={"prenom"}), @ORM\Index(name="id", columns={"id"}), @ORM\Index(name="email", columns={"email"}), @ORM\Index(name="nom", columns={"nom"})})
 * @ORM\Entity
 * @UniqueEntity(fields={"email"}, message="Un utilisateur existe déjà avec cette adresse email.")
 */

class Utilisateur implements UserInterface
{

    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("utilisateur")
     */
    private $id;

    /**
     * @var string|null
     * @Groups("utilisateur")
     * @ORM\Column(name="nom", type="string", length=20, nullable=true)
     */
    private $nom;

    /**
     *
     * @var string|null
     *@Groups("utilisateur")
     * @ORM\Column(name="prenom", type="string", length=20, nullable=true)
     */
    private $prenom;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="date_naissance", type="date", nullable=true)
     * @Assert\LessThan("today",
     * message ="la date est invalide")
     */
    private $dateNaissance;

    /**
     * @var string|null
     * @ORM\Column(name="adresse", type="string", length=20, nullable=true)
     */
    private $adresse;

    /**
     * @var string|null
     * @ORM\Column(name="num_tel", type="string", length=20, nullable=true)
     */
    private $numTel;

    /**
     * @var string|null
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     * @Groups("utilisateur")
     */
    private $email;

    public $confmdp;
    /**
     * @var string|null
     * @ORM\Column(name="mdp", type="string", length=100, nullable=true)
     */
    private $mdp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=true)
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=20, nullable=true)
     */
    private $description;

    /**
     * @var int|null
     *
     * @ORM\Column(name="evaluation", type="integer", nullable=true)
     */
    private $evaluation;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="idclient",cascade={"all"},orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=CommentLike::class, mappedBy="user",cascade={"all"},orphanRemoval=true)
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=CommentDislike::class, mappedBy="user",cascade={"all"},orphanRemoval=true)
     */
    private $dislikes;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
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
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }

    /**
     * @param \DateTime|null $dateNaissance
     */
    public function setDateNaissance(?\DateTime $dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }

    /**
     * @return string|null
     */
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    /**
     * @param string|null $adresse
     */
    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string|null
     */
    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    /**
     * @param string|null $numTel
     */
    public function setNumTel(?string $numTel): void
    {
        $this->numTel = $numTel;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->mdp;
    }

    /**
     * @param string|null $mdp
     */
    public function setMdp(?string $mdp): void
    {
        $this->mdp = $mdp;
    }

    /**
     * @return string|null
     */
    public function getMdp(): ?string
    {
        return $this->mdp;
    }


    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int|null
     */
    public function getEvaluation(): ?int
    {
        return $this->evaluation;
    }





    /**
     * @param int|null $evaluation
     */
    public function setEvaluation(?int $evaluation): void
    {
        $this->evaluation = $evaluation;
    }



    /**
     * @var int
     *
     * @ORM\Column(name="id_interest", type="integer", nullable=false)
     */
    private $idInterest;


    public function getIdInterest(): ?int
    {
        return $this->idInterest;
    }

    public function setIdInterest(int $idInterest): self
    {
        $this->idInterest = $idInterest;

        return $this;
    }




    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;

    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setIdclient($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdclient() === $this) {
                $commentaire->setIdclient(null);
            }
        }

        return $this;
    }

    public function  getIdd():int
    {
        return $this->id;
    }
    public function getRoles()
    {
        $roles= $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }



    public function getSalt()
    {
        return $this->id;


    }

    public function getUsername():?string
    {
        return $this->email;
    }
    public function setUsername():?string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return mixed
     */
    public function getConfmdp()
    {
        return $this->confmdp;
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
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(CommentLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
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
            $dislike->setUser($this);
        }

        return $this;
    }

    public function removeDislike(CommentDislike $dislike): self
    {
        if ($this->dislikes->removeElement($dislike)) {
            // set the owning side to null (unless already changed)
            if ($dislike->getUser() === $this) {
                $dislike->setUser(null);
            }
        }

        return $this;
    }
    /**
     * @ORM\Column(name="googleAuthenticatorSecret", type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return $this->googleAuthenticatorSecret ? true : false;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->email;
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleAuthenticatorSecret;
    }
    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): void
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


}