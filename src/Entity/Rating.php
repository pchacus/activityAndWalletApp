<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RatingRepository")
 */
class Rating
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $eventId;

    /**
     * @ORM\Column(type="integer")
     */
    private $giverId;

    /**
     * @ORM\Column(type="integer")
     */
    private $ratedUserId;

    /**
     * @ORM\Column(type="integer")
     */
    private $gameLevelGrade;

    /**
     * @ORM\Column(type="integer")
     */
    private $userTrustGrade;

    /**
     * @ORM\Column(type="integer")
     */
    private $cultureAndAttitudeGrade;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }

    public function getGiverId(): ?int
    {
        return $this->giverId;
    }

    public function setGiverId(int $giverId): self
    {
        $this->giverId = $giverId;

        return $this;
    }

    public function getRatedUserId(): ?int
    {
        return $this->ratedUserId;
    }

    public function setRatedUserId(int $ratedUserId): self
    {
        $this->ratedUserId = $ratedUserId;

        return $this;
    }

    public function getGameLevelGrade(): ?int
    {
        return $this->gameLevelGrade;
    }

    public function setGameLevelGrade(int $gameLevelGrade): self
    {
        $this->gameLevelGrade = $gameLevelGrade;

        return $this;
    }

    public function getUserTrustGrade(): ?int
    {
        return $this->userTrustGrade;
    }

    public function setUserTrustGrade(int $userTrustGrade): self
    {
        $this->userTrustGrade = $userTrustGrade;

        return $this;
    }

    public function getCultureAndAttitudeGrade(): ?int
    {
        return $this->cultureAndAttitudeGrade;
    }

    public function setCultureAndAttitudeGrade(int $cultureAndAttitudeGrade): self
    {
        $this->cultureAndAttitudeGrade = $cultureAndAttitudeGrade;

        return $this;
    }
}
