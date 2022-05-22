<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $numberId;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $heigth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\CheckWorkouts", mappedBy="person")
     */
   // private $checkWorkouts;

    public function __construct()
    {
        $this->checkWorkouts = new ArrayCollection();
    }

     /**
     * @return Collection|CheckWouuts[]
     */
   public function getCheckWorkouts(): Collection
    {
        return $this->checkWorkouts;
    }



     /**
     * @ORM\OneToMany(targetEntity=CheckWorkouts::class, mappedBy="person")
     * @var CheckWorkouts[]|ArrayCollection
     */
    private $checkWorkouts;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNumberId(): ?string
    {
        return $this->numberId;
    }

    public function setNumberId(?string $numberId): self
    {
        $this->numberId = $numberId;

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

    public function getHeigth(): ?string
    {
        return $this->heigth;
    }

    public function setHeigth(?string $heigth): self
    {
        $this->heigth = $heigth;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'lastName'=> $this->getLastName(),
            'numberId'=> $this->getNumberId(),
            'email'=> $this->getEmail(),
            'heigth'=> $this->getHeigth(),
            'weigth'=>$this->getWeight(),
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => '/person/' .  $this->getId()
                ],
            ]
        ];
    }
}
