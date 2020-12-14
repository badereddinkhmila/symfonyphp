<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientdataRepository;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=PatientdataRepository::class)
 */
class Patientdata
{
     /**
     * @ORM\Id() 
     * @ORM\Column(type="string",nullable=false)
     */
    private $device_id;

    /**
     * @ORM\Id()
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $collect_time;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $blood_pressure;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $heart_beat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $oxygen_level;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $blood_sugar;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $temperature;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $location;



    public function getDeviceid(): ?string
    {
        return $this->device_id;
    }
    public function setDeviceid(?string $device_id): self
    {
        $this->device_id = $device_id;

        return $this;
    }
    public function getBloodpressure(): ?float
    {
        return $this->blood_pressure;
    }

    public function setBloodpressure(?float $blood_pressure): self
    {
        $this->blood_pressure = $blood_pressure;

        return $this;
    }

    public function getHeartbeat(): ?float
    {
        return $this->heart_beat;
    }

    public function setHeartbeat(?float $heart_beat): self
    {
        $this->heart_beat = $heart_beat;

        return $this;
    }

    public function getOxygen(): ?float
    {
        return $this->oxygen_level;
    }

    public function setOxygen(?float $oxygen_level): self
    {
        $this->oxygen_level = $oxygen_level;

        return $this;
    }

    public function getSugar(): ?float
    {
        return $this->blood_sugar;
    }

    public function setSugar(?float $blood_sugar): self
    {
        $this->blood_sugar = $blood_sugar;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCollectTime(): ?\DateTimeInterface
    {
        return $this->collect_time;
    }

    public function setCollectTime(?\DateTimeInterface $collect_time): self
    {
        $this->collect_time = $collect_time;

        return $this;
    }
}
