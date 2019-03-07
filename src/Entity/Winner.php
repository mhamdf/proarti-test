<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WinnerRepository")
 */
class Winner
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
    private $reward_quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reward", inversedBy="winners")
     */
    private $reward;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="winner", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRewardQuantity(): ?int
    {
        return $this->reward_quantity;
    }

    public function setRewardQuantity(int $reward_quantity): self
    {
        $this->reward_quantity = $reward_quantity;

        return $this;
    }

    public function getReward(): ?Reward
    {
        return $this->reward;
    }

    public function setReward(?Reward $reward): self
    {
        $this->reward = $reward;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
