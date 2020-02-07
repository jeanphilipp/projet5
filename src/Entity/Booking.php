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
     * @ORM\ManyToOne(targetEntity="App\Entity\Cat", inversedBy="bookings", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="cat_id", referencedColumnName="id", nullable=false)
     */
    private $cat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startdate): self
    {
        $this->startDate = $startdate;
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

    public function getCat(): ?Cat
    {
        return $this->cat;
    }

    public function setCat(Cat $cat): self
    {
        $this->cat = $cat;
        return $this;
    }
}
