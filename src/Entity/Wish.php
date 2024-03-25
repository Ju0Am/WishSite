<?php

namespace App\Entity;

use App\Repository\WishRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WishRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Wish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    #[Assert\Length(min:3, minMessage:'Le nom doit avoir au minimum{{limit}} caratères')]
    #[Assert\Length(max:250, maxMessage:'Le nom doit avoir au maximum{{limit}} caratères')]
    #[Assert\NotBlank]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min:20, minMessage:'Le nom doit avoir au minimum{{limit}} caratères')]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min:3, minMessage:'Le nom doit avoir au minimum{{limit}} caratères')]
    #[Assert\Length(max:50, maxMessage:'Le nom doit avoir au maximum{{limit}} caratères')]
    private ?string $author = null;

    #[ORM\Column]
    private ?bool $isPublished = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateUpdated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }
    #[Orm\PrePersist]
    public function setDateCreated(): static
    {
        $this->dateCreated = new \DateTime();

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }
    #[Orm\PreUpdate]
    public function setDateUpdated(): static
    {
        $this->dateUpdated = new \DateTime();

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

}
