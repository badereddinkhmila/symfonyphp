<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer",unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex (
     *     pattern="/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix",
     *     message = "Le format de l'e-mail '{{ value }}' n'est pas valide",
     *     match=true
     * )
     * @Assert\Email(
     *     message = "Le format de l'e-mail '{{ value }}' n'est pas valide",
     *     mode="strict"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min =10)
     * @Assert\Regex(
     * pattern = "/^(?=.*\d)(?=.*[A-Z])(?=.*[@#£!$%])(?!.*(.)\1{2}).*[a-z]/m",
     * match=true,
     * message="Votre mot de passe doit comporter au moins 10 caractères, dont des lettres majuscules et minuscules, un chiffre et un symbole.")
     */

    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Assert\LessThanOrEqual("-18 years",
     *     message="Vous devez avoir au moins 18 ans ou plus.")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="Users",cascade={"persist"})
     */
    private $userRoles;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDoctor;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="patients")
     * @ORM\JoinTable(name="patients",joinColumns={
     *          @ORM\JoinColumn(name="doctor_id",referencedColumnName="id")
     *          },
     *          inverseJoinColumns={@ORM\JoinColumn(name="patient_id",referencedColumnName="id")})
     */
    private $doctor;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="doctor")
     * 
     */
    private $patients;

    /**
     * @ORM\ManyToMany(targetEntity=Randezvous::class, mappedBy="parts")
     */
    private $randezvouses;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Complaint::class, mappedBy="complaint_creator", orphanRemoval=true)
     */
    private $complaints;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $length;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ever_married;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ever_smoked;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;


    /**
     * @ORM\OneToOne(targetEntity=SensorGateway::class, mappedBy="patient_sg", cascade={"persist", "remove"})
     */
    private $sensorGateway;

    /**
     * @ORM\OneToMany(targetEntity=PaquetRequest::class, mappedBy="issuer", orphanRemoval=true)
     */
    private $paquetRequests;



    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->doctor = new ArrayCollection();
        $this->patients = new ArrayCollection();
        $this->randezvouses = new ArrayCollection();
        $this->complaints = new ArrayCollection();
        $this->paquetRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(){
        $roles=$this->userRoles->map(function ($role)
        { return $role->getTitle();})->toArray();
        $roles[]='ROLE_USER';
        return $roles;    
    }
    public function getUsername()
    {
        return $this->email;
    }
    public function getSalt(){}

    public function eraseCredentials(){}

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }
        return $this;
    }

    public function getIsDoctor(): ?bool
    {
        return $this->isDoctor;
    }

    public function setIsDoctor(?bool $isDoctor): self
    {
        $this->isDoctor = $isDoctor;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getDoctor(): Collection
    {
        return $this->doctor;
    }

    public function addDoctor(self $doctor): self
    {
        if (!$this->doctor->contains($doctor)) {
            $this->doctor[] = $doctor;
        }

        return $this;
    }

    public function removeDoctor(self $doctor): self
    {
        if ($this->doctor->contains($doctor)) {
            $this->doctor->removeElement($doctor);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(self $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->addDoctor($this);
        }

        return $this;
    }

    public function removePatient(self $patient): self
    {
        if ($this->patients->contains($patient)) {
            $this->patients->removeElement($patient);
            $patient->removeDoctor($this);
        }

        return $this;
    }

    /**
     * @return Collection|Randezvous[]
     */
    public function getRandezvouses(): Collection
    {
        return $this->randezvouses;
    }

    public function addRandezvouse(Randezvous $randezvouse): self
    {
        if (!$this->randezvouses->contains($randezvouse)) {
            $this->randezvouses[] = $randezvouse;
            $randezvouse->addPart($this);
        }

        return $this;
    }

    public function removeRandezvouse(Randezvous $randezvouse): self
    {
        if ($this->randezvouses->contains($randezvouse)) {
            $this->randezvouses->removeElement($randezvouse);
            $randezvouse->removePart($this);
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(){
        
        if(empty($this->age)){
            $date= new \DateTime();
            $dif = $date->diff($this->birthdate);
            $this->age = floor($dif->days/365);}

        if(empty($this->createdAt)){
            $this->createdAt =new \DateTime();
        }

        if(empty($this->avatar) && $this->gender=="Homme"){
            $this->avatar="/images/M-avatar.jpg";
        }
        elseif(empty($this->avatar) && $this->gender=="Femme"){
            $this->avatar="/images/F-avatar.jpg";
        }
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active): void
    {
        $this->active = $active;
    }
    /**
     * @return mixed
     */
    public function getEverSmoked()
    {
        return $this->ever_smoked;
    }

    /**
     * @param mixed $ever_smoked
     */
    public function setEverSmoked($ever_smoked): void
    {
        $this->ever_smoked = $ever_smoked;
    }

    /**
     * @return mixed
     */
    public function getEverMarried()
    {
        return $this->ever_married;
    }

    /**
     * @param mixed $ever_married
     */
    public function setEverMarried($ever_married): void
    {
        $this->ever_married = $ever_married;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return Collection|Complaint[]
     */
    public function getComplaints(): Collection
    {
        return $this->complaints;
    }

    public function addComplaint(Complaint $complaint): self
    {
        if (!$this->complaints->contains($complaint)) {
            $this->complaints[] = $complaint;
            $complaint->setComplaintCreator($this);
        }

        return $this;
    }

    public function removeComplaint(Complaint $complaint): self
    {
        if ($this->complaints->contains($complaint)) {
            $this->complaints->removeElement($complaint);
            // set the owning side to null (unless already changed)
            if ($complaint->getComplaintCreator() === $this) {
                $complaint->setComplaintCreator(null);
            }
        }

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(?float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getSensorGateway(): ?SensorGateway
    {
        return $this->sensorGateway;
    }

    public function setSensorGateway(?SensorGateway $sensorGateway): self
    {
        $this->sensorGateway = $sensorGateway;

        // set (or unset) the owning side of the relation if necessary
        $newPatient_sg = null === $sensorGateway ? null : $this;
        if ($sensorGateway->getPatientSg() !== $newPatient_sg) {
            $sensorGateway->setPatientSg($newPatient_sg);
        }

        return $this;
    }

    /**
     * @return Collection|PaquetRequest[]
     */
    public function getPaquetRequests(): Collection
    {
        return $this->paquetRequests;
    }

    public function addPaquetRequest(PaquetRequest $paquetRequest): self
    {
        if (!$this->paquetRequests->contains($paquetRequest)) {
            $this->paquetRequests[] = $paquetRequest;
            $paquetRequest->setIssuer($this);
        }

        return $this;
    }

    public function removePaquetRequest(PaquetRequest $paquetRequest): self
    {
        if ($this->paquetRequests->contains($paquetRequest)) {
            $this->paquetRequests->removeElement($paquetRequest);
            // set the owning side to null (unless already changed)
            if ($paquetRequest->getIssuer() === $this) {
                $paquetRequest->setIssuer(null);
            }
        }

        return $this;
    }

}