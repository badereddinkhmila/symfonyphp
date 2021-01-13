<?php

namespace App\Entity;

use App\Repository\BloodSugarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BloodSugarRepository::class)
 */
class BloodSugar
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
    private ?float $bSugar_value;

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


    public function getBSugarValue(): ?float
    {
        return $this->bSugar_value;
    }

    public function setBSugarValue(float $bSugar_value): self
    {
        $this->bSugar_value = $bSugar_value;

        return $this;
    }
}
