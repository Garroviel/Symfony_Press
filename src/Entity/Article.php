<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['slug'], message: 'Ce slug est déjà utilisé.')]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
	#[Assert\NotBlank(message: 'Le titre est obligatoire.')]
	#[Assert\Length(
		min: 5,
		max: 255,
		minMessage: 'Le titre doit contenir au moins {{ limit }} caractères.',
		maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.'
	)]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
	// #[Assert\NotBlank(message: 'Le slug est obligatoire.')]
	#[Assert\Length(
		min: 5,
		minMessage: 'Le contenu doit contenir au moins {{ limit }} caractères.'
	)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
	#[Assert\NotBlank(message: 'Le contenu est obligatoire.')]
	#[Assert\Length(
		min: 20,
		minMessage: 'Le contenu doit contenir au moins {{ limit }} caractères.'
	)]
    private ?string $content = null;

    #[ORM\Column]
	#[Assert\NotNull(message: 'La date de création est obligatoire.')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
	#[Assert\NotNull(message: 'Veuillez sélectionner une catégorie.')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

	public function __construct()
	{
		$this->createdAt = new \DateTimeImmutable();
	}

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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
