<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $activity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $placeOfMetting;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfSeats;

    /**
     * @ORM\Column(type="integer")
     */
    private $creatorId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usersListId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eventPhotoName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $currentlyModifying;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getPlaceOfMetting(): ?string
    {
        return $this->placeOfMetting;
    }

    public function setPlaceOfMetting(string $placeOfMetting): self
    {
        $this->placeOfMetting = $placeOfMetting;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNumberOfSeats(): ?int
    {
        return $this->numberOfSeats;
    }

    public function setNumberOfSeats(int $numberOfSeats): self
    {
        $this->numberOfSeats = $numberOfSeats;

        return $this;
    }

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    public function setCreatorId(int $creatorId): self
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    public function getUsersListId(): ?string
    {
        return $this->usersListId;
    }

    public function setUsersListId(string $usersListId): self
    {
        $this->usersListId = $usersListId;

        return $this;
    }

    public function getEventPhotoName(): ?string
    {
        return $this->eventPhotoName;
    }

    public function setEventPhotoName(?string $eventPhotoName): self
    {
        $this->eventPhotoName = $eventPhotoName;

        return $this;
    }

    public function getCurrentlyModifying(): ?bool
    {
        return $this->currentlyModifying;
    }

    public function setCurrentlyModifying(bool $currentlyModifying): self
    {
        $this->currentlyModifying = $currentlyModifying;

        return $this;
    }



}
