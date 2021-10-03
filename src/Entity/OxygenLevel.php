<?php

namespace App\Entity;

use App\Repository\OxygenLevelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OxygenLevelRepository::class)
 */
class OxygenLevel
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
    private ?float $pulse;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $spo2;


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

    /**
     * Get the value of spo2
     */ 
    public function getSpo2()
    {
        return $this->spo2;
    }

    /**
     * Set the value of spo2
     *
     * @return  self
     */ 
    public function setSpo2($spo2)
    {
        $this->spo2 = $spo2;

        return $this;
    }

    /**
     * Get the value of pulse
     */ 
    public function getPulse()
    {
        return $this->pulse;
    }

    /**
     * Set the value of pulse
     *
     * @return  self
     */ 
    public function setPulse($pulse)
    {
        $this->pulse = $pulse;

        return $this;
    }
}
