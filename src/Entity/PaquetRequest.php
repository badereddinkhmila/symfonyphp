<?php

namespace App\Entity;

use App\Repository\PaquetRequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaquetRequestRepository::class)
 *
 * @ORM\HasLifecycleCallbacks()
 */
class PaquetRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gateway;

    /**
     * @ORM\Column(type="boolean")
     */
    private $glucose_sensor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $oxygen_sensor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $blood_pressure_sensor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $temperature;

    /**
     * @ORM\Column(type="boolean")
     */
    private $weight;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="paquetRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $issuer;

    /**
     * @ORM\Column(type="boolean")
     */
    private $approved;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGateway(): ?bool
    {
        return $this->gateway;
    }

    public function setGateway(bool $gateway): self
    {
        $this->gateway = $gateway;

        return $this;
    }

    public function getGlucoseSensor(): ?bool
    {
        return $this->glucose_sensor;
    }

    public function setGlucoseSensor(?bool $glucose_sensor): self
    {
        $this->glucose_sensor = $glucose_sensor;

        return $this;
    }

    public function getOxygenSensor(): ?bool
    {
        return $this->oxygen_sensor;
    }

    public function setOxygenSensor(bool $oxygen_sensor): self
    {
        $this->oxygen_sensor = $oxygen_sensor;

        return $this;
    }

    public function getBloodPressureSensor(): ?bool
    {
        return $this->blood_pressure_sensor;
    }

    public function setBloodPressureSensor(bool $blood_pressure_sensor): self
    {
        $this->blood_pressure_sensor = $blood_pressure_sensor;

        return $this;
    }

    public function getTemperature(): ?bool
    {
        return $this->temperature;
    }

    public function setTemperature(bool $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getWeight(): ?bool
    {
        return $this->weight;
    }

    public function setWeight(bool $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getIssuer(): ?User
    {
        return $this->issuer;
    }

    public function setIssuer(?User $issuer): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    public function getApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(){
        if(empty($this->created_at)){
            $this->created_at =new \DateTime();
        }
    }
}
