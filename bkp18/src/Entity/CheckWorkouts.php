<?php

namespace App\Entity;

use App\Repository\CheckWorkoutsRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=CheckWorkoutsRepository::class)
 */
class CheckWorkouts implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;


     /**
     * Many Workouts have one . This is the owning side.
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="checkWorkouts")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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


     /**
     * Many Workouts have one . This is the owning side.
     * @ORM\ManyToOne(targetEntity="Workouts", inversedBy="workouts")
     * @ORM\JoinColumn(name="workouts_id", referencedColumnName="id")
     */
    private $workouts;

    /**
     * @ORM\Column(type="json")
     */
    private $trainingCheck;

    public function getWorkouts(): ?Workouts
    {
        return $this->workouts;
    }

    public function setWorkouts(?Workouts $workouts): self
    {
        $this->workouts = $workouts;

        return $this;
    }

    public function getTrainingCheck(): ?string
    {
        return $this->trainingCheck;
    }

    public function setTrainingCheck(string $trainingCheck): self
    {
        $this->trainingCheck = $trainingCheck;

        return $this;
    }
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'created' => $this->getCreated(),
            'person' => [
                'id' => $this->getPerson()->getId(),
                'firstName' => $this->getPerson()->getFirstName(),
                'lastName' => $this->getPerson()->getLastName()
            ],
            'workouts' => [
                'id' => $this->getWorkouts()->getId(),
                'title' => $this->getWorkouts()->getTitle(),
                'description' => $this->getWorkouts()->getDescription()
            ], 
            'training_check' => json_decode($this->getTrainingCheck(),true),
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => '/checkworkout/' .  $this->getId()
                ],
            ]
        ];
    }
}
