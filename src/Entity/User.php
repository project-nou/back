<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $is_active;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Group::class)]
    private $groups;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'participants')]
    private $groups_their_in;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->groups_their_in = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->setAdmin($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getAdmin() === $this) {
                $group->setAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroupsTheirIn(): Collection
    {
        return $this->groups_their_in;
    }

    public function addGroupsTheirIn(Group $groupsTheirIn): self
    {
        if (!$this->groups_their_in->contains($groupsTheirIn)) {
            $this->groups_their_in[] = $groupsTheirIn;
            $groupsTheirIn->addParticipant($this);
        }

        return $this;
    }

    public function removeGroupsTheirIn(Group $groupsTheirIn): self
    {
        if ($this->groups_their_in->removeElement($groupsTheirIn)) {
            $groupsTheirIn->removeParticipant($this);
        }

        return $this;
    }
}
