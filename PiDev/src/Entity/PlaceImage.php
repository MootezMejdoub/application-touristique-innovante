<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlaceImage
 *
 * @ORM\Table(name="place_image")
 * @ORM\Entity
 */
class PlaceImage
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
     * @var Place
     *
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idplace", referencedColumnName="id")
     * })
     */
    private Place $idplace;

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
    public function getIdplace(): Place
    {
        return $this->idplace;
    }

    /**
     * @param int $idplace
     */
    public function setIdplace(Place $idplace): void
    {
        $this->idplace = $idplace;
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
