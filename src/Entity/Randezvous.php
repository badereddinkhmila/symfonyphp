<?php

namespace App\Entity;

use DateTime;
use DateInterval;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RandezvousRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=RandezvousRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Randezvous
{
     /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer",unique=true)
     */
    private ?int $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      max = 400,
     *      maxMessage = "Votre description ne peut pas dépasser {{ limite }} caractères.")
     */
    private ?string $description="";

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isValid=false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @Assert\NotBlank
     * @Assert\GreaterThan("today")
     * @ORM\Column(type="datetime")
     */
    private $dated_for;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="randezvouses",fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="randezvous_user")
     */
    private $parts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="time")
     */
    private $end_in;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;


    public function __construct()
    {
        $this->parts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $isValid): self
    {
        $this->isValid = $isValid;

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

    public function getDatedFor(): ?\DateTimeInterface
    {
        return $this->dated_for;
    }

    public function setDatedFor(\DateTimeInterface $dated_for): self
    {
        $this->dated_for = $dated_for;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function addPart(User $part): self
    {
        if (!$this->parts->contains($part)) {
            $this->parts[] = $part;
        }

        return $this;
    }

    public function removePart(User $part): self
    {
        if ($this->parts->contains($part)) {
            $this->parts->removeElement($part);
        }

        return $this;
    }
    /**
    * @ORM\PrePersist
    *
    */
    public function createTimestamps(): void
    {
        $this->setCreatedAt(new \DateTime('now'));    
    }

public function getColor(): ?string
{
    return $this->color;
}

public function setColor(string $color): self
{
    $this->color = $color;

    return $this;
}

public function getEndIn(): ?\DateTimeInterface
{
    return $this->end_in;
}

public function setEndIn(\DateTimeInterface $end_in): self
{
    $this->end_in = $end_in;

    return $this;
}

public function getType(): ?string
{
    return $this->type;
}

public function setType(string $type): self
{
    $this->type = $type;

    return $this;
}
}
