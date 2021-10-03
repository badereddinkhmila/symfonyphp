<?php

namespace App\Entity;

use App\Repository\WeightRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeightRepository::class)
 */
class Weight
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string",nullable=false)
     */
    private ?string $device_id;

    /**
     * @ORM\Id()
     * @ORM\Column(type="datetime", nullable=false)
     */
    private ?\DateTimeInterface $collect_time;


    /**
     * @ORM\Column(type="float")
     */
    private $bmi;

    /**
     * @ORM\Column(type="float")
     */
    private $bodyfat;
    
    /**
     * @ORM\Column(type="float")
     */
    private $weight;
    
    
    public function getDeviceId(): ?string
    {
        return $this->device_id;
    }

    public function setDeviceId(string $device_id): self
    {
        $this->device_id = $device_id;

        return $this;
    }

    public function getCollectTime(): ?\DateTimeInterface
    {
        return $this->collect_time;
    }

    public function setCollectTime(\DateTimeInterface $collect_time): self
    {
        $this->collect_time = $collect_time;

        return $this;
    }


    public function getBmi(): ?float
    {
        return $this->bmi;
    }

    public function setBmi(float $bmi): self
    {
        $this->weight = $bmi;

        return $this;
    }


    public function getBodyfat(): ?float
    {
        return $this->bodyfat;
    }

    public function setBodyfat(float $bodyfat): self
    {
        $this->bodyfat = $bodyfat;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

}
