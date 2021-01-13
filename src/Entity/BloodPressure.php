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
    private $bp_value;

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


    public function getBpValue(): ?float
    {
        return $this->bp_value;
    }

    public function setBpValue(float $bp_value): self
    {
        $this->bp_value = $bp_value;

        return $this;
    }
}
