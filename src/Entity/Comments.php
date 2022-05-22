<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments implements \JsonSerializable
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
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * Many Workouts have one . This is the owning side.
     * @ORM\ManyToOne(targetEntity="Workouts", inversedBy="workouts")
     * @ORM\JoinColumn(name="workouts_id", referencedColumnName="id")
     */
    private $workouts;

    public function getWorkouts(): ?Workouts
    {
        return $this->workouts;
    }

    public function setWorkouts(?Workouts $workouts): self
    {
        $this->workouts = $workouts;

        return $this;
    }

     /**
     * Many Workouts have one . This is the owning side.
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @ORM\Column(type="integer")
     */
    private $stars;

    public function getPerson(): ?Workouts
    {
        return $this->workouts;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(int $stars): self
    {
        $this->stars = $stars;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'description' => $this->getDescription(),
            'stars' => $this->getStars(),
            'created' => $this->getCreated(),
            'workoutsId'=>$this->getWorkouts()->getId(),
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => '/workout/comments/' .  $this->getId()
                ],
            ]
        ];
    }

  
}
