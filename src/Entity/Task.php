<?php

namespace App\Entity;

use App\Enum\Status;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_title_name_user', columns: ['title', 'user_id'])]
#[UniqueEntity(
    fields: ['title', 'user'],
    message: 'Ya tienes una tarea con este nombre.'
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(enumType: Status::class)]
    private ?Status $status = Status::pending;

    #[ORM\Column]
    private ?bool $isPinned = false;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Folder $Folder = null;


    #[ORM\ManyToOne(inversedBy: 'Task')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'Task')]
    private ?Priority $priority = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isPinned(): ?bool
    {
        return $this->isPinned;
    }

    public function setIsPinned(bool $isPinned): static
    {
        $this->isPinned = $isPinned;

        return $this;
    }

    public function getFolder(): ?Folder
    {
        return $this->Folder;
    }

    public function setFolder(?Folder $Folder): static
    {
        $this->Folder = $Folder;

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

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }
}
