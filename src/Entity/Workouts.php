<?php

namespace App\Entity;

use App\Repository\WorkoutsRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=WorkoutsRepository::class)
 */
class Workouts implements JsonSerializable
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
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $time;


     /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * One workouts has many comments. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="workouts")
     */
    private $comments;

    public function getComments(): ?Comments
    {
        return $this->comments;
    }

    public function setComments(?Comments $comments): self
    {
        $this->comments = $comments;

        return $this;
    }


    /**
     * Many Workouts have one . This is the owning side.
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    public function getPerson(): ?Workouts
    {
        return $this->workouts;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

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
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'time'=> $this->getTime(),
            'created' => $this->getCreated(),
            '_links' => [
                [
                    'rel' => 'self',
                    'path' => '/workout/' .  $this->getId()
                ],
            ]
        ];
    }
}
