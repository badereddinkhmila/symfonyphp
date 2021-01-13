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
    private ?float $oxygen_level;


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

    public function getOxygenLevel(): ?float
    {
        return $this->oxygen_level;
    }

    public function setOxygenLevel(float $oxygen_level): self
    {
        $this->oxygen_level = $oxygen_level;

        return $this;
    }
}
