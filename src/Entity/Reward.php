<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RewardRepository")
 */
class Reward
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
    private $reward;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Winner", mappedBy="reward")
     */
    private $winners;

    public function __construct()
    {
        $this->winners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReward(): ?string
    {
        return $this->reward;
    }

    public function setReward(string $reward): self
    {
        $this->reward = $reward;

        return $this;
    }

    /**
     * @return Collection|Winner[]
     */
    public function getWinners(): Collection
    {
        return $this->winners;
    }

    public function addWinner(Winner $winner): self
    {
        if (!$this->winners->contains($winner)) {
            $this->winners[] = $winner;
            $winner->setReward($this);
        }

        return $this;
    }

    public function removeWinner(Winner $winner): self
    {
        if ($this->winners->contains($winner)) {
            $this->winners->removeElement($winner);
            // set the owning side to null (unless already changed)
            if ($winner->getReward() === $this) {
                $winner->setReward(null);
            }
        }

        return $this;
    }
}
