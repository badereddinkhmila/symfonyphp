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
    private ?float $mg_dl;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $mmol_l;    

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
     * Get the value of mg_dl
     */ 
    public function getMg_dl()
    {
        return $this->mg_dl;
    }

    /**
     * Set the value of mg_dl
     *
     * @return  self
     */ 
    public function setMg_dl($mg_dl)
    {
        $this->mg_dl = $mg_dl;

        return $this;
    }

    /**
     * Get the value of mmol_l
     */ 
    public function getMmol_l()
    {
        return $this->mmol_l;
    }

    /**
     * Set the value of mmol_l
     *
     * @return  self
     */ 
    public function setMmol_l($mmol_l)
    {
        $this->mmol_l = $mmol_l;

        return $this;
    }
}
