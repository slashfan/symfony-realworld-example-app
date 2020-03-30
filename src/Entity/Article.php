<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Table(name="rw_article")
 *
 * @UniqueEntity("slug")
 */
class Article
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="article.title.not_blank")
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"title"}, updatable=true, unique=true)
     */
    private ?string $slug = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="article.description.not_blank")
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="article.body.not_blank")
     */
    private ?string $body = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private ?User $author = null;

    /**
     * @var Collection|Tag[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", cascade={"persist"})
     * @ORM\JoinTable(name="rw_article_tag")
     */
    private Collection $tags;

    /**
     * @var Collection|User[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="favorites", fetch="EXTRA_LAZY")
     */
    private Collection $favoritedBy;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->favoritedBy = new ArrayCollection();
    }

    public function __toString(): string
    {
        return \sprintf('%s', $this->title);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Collection|Tag[] $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavoritedBy(): Collection
    {
        return $this->favoritedBy;
    }

    public function getFavoritedByCount(): int
    {
        return $this->favoritedBy->count();
    }
}
