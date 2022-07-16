<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanImage
 *
 * @ORM\Table(name="plan_image")
 * @ORM\Entity
 */
class PlanImage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var Plan
     *
     * @ORM\ManyToOne(targetEntity="Plan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idplan", referencedColumnName="id")
     * })
     */
    private Plan $idplan;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=400, nullable=false)
     */
    private $path;

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
     * @return int
     */
    public function getIdplan(): Plan
    {
        return $this->idplan;
    }

    /**
     * @param int $idplan
     */
    public function setIdplan(Plan $idplan): void
    {
        $this->idplan = $idplan;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }


}
