<?php

namespace App\Entity;

use App\Enum\BacklogItemStatus;
use App\Enum\BacklogItemType;
use App\Repository\BacklogItemRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BacklogItemRepository::class)]

#[ORM\Table(name: 'backlog_item', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'uniq_backlog_album', columns: ['backlog_id', 'type', 'album_id']),
    new ORM\UniqueConstraint(name: 'uniq_backlog_artist', columns: ['backlog_id', 'type', 'artist_id']),
])]

#[UniqueEntity(fields: ['backlog', 'type', 'album'], message: 'This album is already in the backlog.')]
#[UniqueEntity(fields: ['backlog', 'type', 'artist'], message: 'This artist is already in the backlog.')]

class BacklogItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Backlog $backlog = null;

    #[ORM\ManyToOne]
    private ?Artist $artist = null;

    #[ORM\ManyToOne]
    private ?Album $album = null;

    #[ORM\Column(nullable: true, enumType: BacklogItemStatus::class)]
    private ?BacklogItemStatus $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(enumType: BacklogItemType::class)]
    private ?BacklogItemType $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBacklog(): ?Backlog
    {
        return $this->backlog;
    }

    public function setBacklog(?Backlog $backlog): static
    {
        $this->backlog = $backlog;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): static
    {
        $this->album = $album;

        return $this;
    }

    public function getStatus(): ?BacklogItemStatus
    {
        return $this->status;
    }

    public function setStatus(?BacklogItemStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getType(): ?BacklogItemType
    {
        return $this->type;
    }

    public function setType(BacklogItemType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
