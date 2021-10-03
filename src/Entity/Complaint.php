<?php

namespace App\Entity;

use App\Repository\ComplaintRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComplaintRepository", repositoryClass=ComplaintRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Complaint
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $complaint_type;

    /**
     * @ORM\Column(type="text")
     */
    private $complaint_description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="complaints")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $complaint_creator;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_treated=false;


    /**
     * @ORM\Column(type="datetime",nullable=false)
     */
    private ?\DateTime $createdAt;

    /**
    * @ORM\Column(type="datetime",nullable=true)
     */
    private ?\DateTime $updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComplaintType(): ?string
    {
        return $this->complaint_type;
    }

    public function setComplaintType(string $complaint_type): self
    {
        $this->complaint_type = $complaint_type;

        return $this;
    }

    public function getComplaintDescription(): ?string
    {
        return $this->complaint_description;
    }

    public function setComplaintDescription(string $complaint_description): self
    {
        $this->complaint_description = $complaint_description;

        return $this;
    }

    public function getComplaintCreator(): ?User
    {
        return $this->complaint_creator;
    }

    public function setComplaintCreator(?User $complaint_creator): self
    {
        $this->complaint_creator = $complaint_creator;

        return $this;
    }

    public function getIsTreated(): ?bool
    {
        return $this->is_treated;
    }

    public function setIsTreated(bool $is_treated): self
    {
        $this->is_treated = $is_treated;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setUpdated(): self
    {
        $this->updated = new \DateTime();
        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(){
        if(empty($this->createdAt)){
            $this->createdAt =new \DateTime();
        }
    }
}
