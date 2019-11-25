<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CatRepository")
 */
class Cat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $catName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkUser;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Booking", mappedBy="fkCat", cascade={"persist", "remove"})
     */
    private $booking;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatName(): ?string
    {
        return $this->catName;
    }

    public function setCatName(string $catname): self
    {
        $this->catName = $catname;

        return $this;
    }

    public function getFkUser(): ?User
    {
        return $this->fkUser;
    }

    public function setFkUser(?User $fkUser): self
    {
        $this->fkUser = $fkUser;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(Booking $booking): self
    {
        $this->booking = $booking;

        // set the owning side of the relation if necessary
        if ($booking->getFkCat() !== $this) {
            $booking->setFkCat($this);
        }

        return $this;
    }
}
