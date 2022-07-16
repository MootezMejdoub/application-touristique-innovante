<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Interest
 *
 * @ORM\Table(name="interest")
 * @ORM\Entity
 */
class Interest
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_intrest", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("posts:interest")
     *
     */
    private $idIntrest;

    /**
     *
     * @ORM\Column(name="sport", type="boolean", nullable=false)
     * @Groups("posts:interest")
     */
    private $sport;

    /**
     *
     * @ORM\Column(name="history", type="boolean", nullable=false)
     * @Groups("posts:interest")
     */

    private $history;

    /**
     *
     * @ORM\Column(name="food", type="boolean", nullable=false)
     * @Groups("posts:interest")
     */

    private $food;

    /**
     *
     * @ORM\Column(name="health", type="boolean", nullable=false)
     * @Groups("posts:interest")
     */
    private $health;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     * @Groups("posts:interest")
     */
    private $score;

    public function getIdIntrest(): ?int
    {
        return $this->idIntrest;
    }

    public function getSport(): ?bool
    {
        return $this->sport;
    }

    public function setSport(bool $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

    public function getHistory(): ?bool
    {
        return $this->history;
    }

    public function setHistory(bool $history): self
    {
        $this->history = $history;

        return $this;
    }

    public function getFood(): ?bool
    {
        return $this->food;
    }

    public function setFood(bool $food): self
    {
        $this->food = $food;

        return $this;
    }

    public function getHealth(): ?bool
    {
        return $this->health;
    }

    public function setHealth(bool $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }


}
