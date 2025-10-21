<?php

namespace App\Entity;

use App\Repository\BacklogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BacklogRepository::class)]
class Backlog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, BacklogItem>
     */
    #[ORM\OneToMany(targetEntity: BacklogItem::class, mappedBy: 'backlog')]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, BacklogItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(BacklogItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setBacklog($this);
        }

        return $this;
    }

    public function removeItem(BacklogItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getBacklog() === $this) {
                $item->setBacklog(null);
            }
        }

        return $this;
    }
}
