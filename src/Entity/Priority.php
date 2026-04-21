<?php

namespace App\Entity;

use App\Repository\PriorityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriorityRepository::class)]
class Priority
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'priority')]
    private Collection $Task;

    #[ORM\Column(length: 255, unique:true)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'priorities')]
    private ?User $user = null;


    public function __construct()
    {
        $this->Task = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @return Collection<int, Task>
     */
    public function getTask(): Collection
    {
        return $this->Task;
    }

    public function addTask(Task $task): static
    {
        if (!$this->Task->contains($task)) {
            $this->Task->add($task);
            $task->setPriority($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->Task->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getPriority() === $this) {
                $task->setPriority(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
