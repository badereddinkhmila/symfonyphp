<?php

namespace App\Entity;

use App\Repository\SensorGatewayRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SensorGatewayRepository::class)
 */
class SensorGateway
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     */
    private $sensor_gateway_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $glycose_sensor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $temperature_sensor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oxygene_sensor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bp_sensor;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $weight_sensor;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $is_Active;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="sensorGateway", cascade={"persist", "remove"})
     */
    private $patient_sg;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deploy_date;


    public function getSensorGatewayId(): ?string
    {
        return $this->sensor_gateway_id;
    }

    public function setSensorGatewayId(string $sensor_gateway_id): self
    {
        $this->sensor_gateway_id = $sensor_gateway_id;
        return $this;
    }

    public function getGlycoseSensor(): ?string
    {
        return $this->glycose_sensor;
    }

    public function setGlycoseSensor(?string $glycose_sensor): self
    {
        $this->glycose_sensor = $glycose_sensor;

        return $this;
    }

    public function getTemperatureSensor(): ?string
    {
        return $this->temperature_sensor;
    }

    public function setTemperatureSensor(?string $temperature_sensor): self
    {
        $this->temperature_sensor = $temperature_sensor;

        return $this;
    }

    public function getOxygeneSensor(): ?string
    {
        return $this->oxygene_sensor;
    }

    public function setOxygeneSensor(string $oxygene_sensor): self
    {
        $this->oxygene_sensor = $oxygene_sensor;

        return $this;
    }

    public function getBpSensor(): ?string
    {
        return $this->bp_sensor;
    }

    public function setBpSensor(?string $bp_sensor): self
    {
        $this->bp_sensor = $bp_sensor;

        return $this;
    }

    public function getWeightSensor(): ?string
    {
        return $this->weight_sensor;
    }

    public function setWeightSensor(?string $weight_sensor): self
    {
        $this->weight_sensor = $weight_sensor;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_Active;
    }

    public function setIsActive(bool $is_Active): self
    {
        $this->is_Active = $is_Active;

        return $this;
    }

    public function getPatientSg(): ?User
    {
        return $this->patient_sg;
    }

    public function setPatientSg(?User $patient_sg): self
    {
        $this->patient_sg = $patient_sg;

        return $this;
    }

    public function getDeployDate(): ?\DateTimeInterface
    {
        return $this->deploy_date;
    }

    public function setDeployDate(?\DateTimeInterface $deploy_date): self
    {
        $this->deploy_date = $deploy_date;

        return $this;
    }
}
