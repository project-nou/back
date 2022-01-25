<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'boolean')]
    private $is_active;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'groups')]
    #[ORM\JoinColumn(nullable: false)]
    private $admin;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groups_their_in')]
    private $particpants;

    public function __construct()
    {
        $this->particpants = new ArrayCollection();
    }

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getAdmin(): ?User
    {
        return $this->admin;
    }

    public function setAdmin(?User $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticpants(): Collection
    {
        return $this->particpants;
    }

    public function addParticpant(User $particpant): self
    {
        if (!$this->particpants->contains($particpant)) {
            $this->particpants[] = $particpant;
        }

        return $this;
    }

    public function removeParticpant(User $particpant): self
    {
        $this->particpants->removeElement($particpant);

        return $this;
    }
}
