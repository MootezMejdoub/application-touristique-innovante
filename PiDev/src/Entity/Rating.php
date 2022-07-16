<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rating", indexes={@ORM\Index(name="blogId", columns={"blogId"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RatingRepository")
 */
class Rating
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
     * @var int|null
     *
     * @ORM\Column(name="rateIndex", type="integer", nullable=true)
     */
    private $rateindex;

    /**
     * @var \Blog
     *
     * @ORM\ManyToOne(targetEntity="Blog")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blogId", referencedColumnName="id")
     * })
     */
    private $blogid;

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
     * @return int|null
     */
    public function getRateindex(): ?int
    {
        return $this->rateindex;
    }

    /**
     * @param int|null $rateindex
     */
    public function setRateindex(?int $rateindex): void
    {
        $this->rateindex = $rateindex;
    }


    public function getBlogid(): ?Blog
    {
        return $this->blogid;
    }


    public function setBlogid(?Blog $blogid): self
    {
        $this->blogid = $blogid;
        return $this;
    }


}
