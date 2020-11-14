<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
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
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;
    
    private $passwordConfirmation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="date", nullable=true)
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
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="Users",cascade={"persist", "remove"})
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
     * @ORM\OneToMany(targetEntity=Patientdata::class, mappedBy="personne")
     */
    private $patientdatas;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->doctor = new ArrayCollection();
        $this->patients = new ArrayCollection();
        $this->randezvouses = new ArrayCollection();
        $this->patientdatas = new ArrayCollection();
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
            $this->age = $dif->days/365;}
        
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
     * @return Collection|Patientdata[]
     */
    public function getPatientdatas(): Collection
    {
        return $this->patientdatas;
    }

    public function addPatientdata(Patientdata $patientdata): self
    {
        if (!$this->patientdatas->contains($patientdata)) {
            $this->patientdatas[] = $patientdata;
            $patientdata->setPersonne($this);
        }

        return $this;
    }

    public function removePatientdata(Patientdata $patientdata): self
    {
        if ($this->patientdatas->contains($patientdata)) {
            $this->patientdatas->removeElement($patientdata);
            // set the owning side to null (unless already changed)
            if ($patientdata->getPersonne() === $this) {
                $patientdata->setPersonne(null);
            }
        }

        return $this;
    }
}