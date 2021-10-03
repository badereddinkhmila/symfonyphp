<?php

namespace App\Entity;

use App\Repository\BloodPressureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BloodPressureRepository::class)
 */
class BloodPressure
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
    private $diastolic;

    /**
     * @ORM\Column(type="float")
     */
    private $pulse;

    /**
     * @ORM\Column(type="float")
     */
    private $systolic;

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
     * Get the value of diastolic
     */ 
    public function getDiastolic()
    {
        return $this->diastolic;
    }

    /**
     * Set the value of diastolic
     *
     * @return  self
     */ 
    public function setDiastolic($diastolic)
    {
        $this->diastolic = $diastolic;

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

    /**
     * Get the value of systolic
     */ 
    public function getSystolic()
    {
        return $this->systolic;
    }

    /**
     * Set the value of systolic
     *
     * @return  self
     */ 
    public function setSystolic($systolic)
    {
        $this->systolic = $systolic;

        return $this;
    }
}
