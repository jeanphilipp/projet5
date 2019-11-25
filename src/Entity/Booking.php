<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private $exitDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceStay;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Cat", inversedBy="booking", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkCat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStarDate(\DateTimeInterface $sartdate): self
    {
        $this->startDate = $sartdate;

        return $this;
    }

    public function getExitDate(): ?\DateTimeInterface
    {
        return $this->exitDate;
    }

    public function setExitDate(\DateTimeInterface $exitdate): self
    {
        $this->exitDate = $exitdate;

        return $this;
    }

    public function getPriceStay(): ?int
    {
        return $this->priceStay;
    }

    public function setPriceStay(int $pricestay): self
    {
        $this->priceStay = $pricestay;

        return $this;
    }

    public function getFkCat(): ?Cat
    {
        return $this->fkCat;
    }

    public function setFkCat(Cat $fkCat): self
    {
        $this->fkCat = $fkCat;

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
}
