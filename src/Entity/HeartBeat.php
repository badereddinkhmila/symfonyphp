<?php

namespace App\Entity;

use App\Repository\HeartBeatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HeartBeatRepository::class)
 */
class HeartBeat
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
    private ?float $heart_beat_value;


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

    public function getHeartBeatValue(): ?float
    {
        return $this->heart_beat_value;
    }

    public function setHeartBeatValue(float $heart_beat_value): self
    {
        $this->heart_beat_value = $heart_beat_value;
        return $this;
    }
}
