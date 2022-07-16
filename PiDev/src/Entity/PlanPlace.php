<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanPlace
 *
 * @ORM\Table(name="plan_place", indexes={@ORM\Index(name="idplan", columns={"idplan"}), @ORM\Index(name="idplace", columns={"idplace"})})
 * @ORM\Entity
 */
class PlanPlace
{
    /**
     * @var int
     *
     * @ORM\Column(name="ref", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $ref;

    /**
     * @var Place
     *
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idplace", referencedColumnName="id")
     * })
     */
    private Place $idplace;

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
     * @return int
     */
    public function getRef(): int
    {
        return $this->ref;
    }

    /**
     * @param int $ref
     */
    public function setRef(int $ref): void
    {
        $this->ref = $ref;
    }

    /**
     * @return Place
     */
    public function getIdplace(): Place
    {
        return $this->idplace;
    }

    /**
     * @param Place $idplace
     */
    public function setIdplace(Place $idplace): void
    {
        $this->idplace = $idplace;
    }

    /**
     * @return Plan
     */
    public function getIdplan(): Plan
    {
        return $this->idplan;
    }

    /**
     * @param Plan $idplan
     */
    public function setIdplan(Plan $idplan): void
    {
        $this->idplan = $idplan;
    }



    public function __toString()
    {
        // TODO: Implement __toString() method.
        return sprintf("%s-%s",$this->idplan->getTitre(),$this->idplace->getNom());
    }


}
