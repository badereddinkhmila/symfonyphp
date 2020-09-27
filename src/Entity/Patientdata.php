<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientdataRepository;

/**
 * @ORM\Entity(repositoryClass=PatientdataRepository::class)
 */
class Patientdata
{
     /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer",unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tension;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $oxygene;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $glucose;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $poids;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $temperature;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="patientdatas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $personne;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gatewayid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $aptitude;

    /**
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTension(): ?int
    {
        return $this->tension;
    }

    public function setTension(?int $tension): self
    {
        $this->tension = $tension;

        return $this;
    }

    public function getOxygene(): ?int
    {
        return $this->oxygene;
    }

    public function setOxygene(?int $oxygene): self
    {
        $this->oxygene = $oxygene;

        return $this;
    }

    public function getGlucose(): ?float
    {
        return $this->glucose;
    }

    public function setGlucose(?float $glucose): self
    {
        $this->glucose = $glucose;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): self
    {
        $this->poids = $poids;

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

    public function getPersonne(): ?User
    {
        return $this->personne;
    }

    public function setPersonne(?User $personne): self
    {
        $this->personne = $personne;

        return $this;
    }

    public function getGatewayid(): ?string
    {
        return $this->gatewayid;
    }

    public function setGatewayid(string $gatewayid): self
    {
        $this->gatewayid = $gatewayid;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAptitude(): ?string
    {
        return $this->aptitude;
    }

    public function setAptitude(?string $aptitude): self
    {
        $this->aptitude = $aptitude;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime();

        return $this;
    }
}
