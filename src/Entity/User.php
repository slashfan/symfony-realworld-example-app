<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="rw_user")
 *
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User implements UserInterface
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Url()
     */
    private $image;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="followers")
     */
    private $followed;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="followed")
     * @ORM\JoinTable(
     *   name="rw_user_follower",
     *   joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="follower_id", referencedColumnName="id")}
     * )
     */
    private $followers;

    /**
     * @var ArrayCollection|Article[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", inversedBy="favoritedBy")
     * @ORM\JoinTable(name="rw_user_favorite")
     */
    private $favorites;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->followed = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getBio(): ?string
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function follows(self $user)
    {
        return $this->followed->contains($user);
    }

    /**
     * @param User $user
     */
    public function follow(self $user)
    {
        if ($user->getFollowers()->contains($this)) {
            return;
        }

        $user->getFollowers()->add($this);
    }

    /**
     * @param User $user
     */
    public function unfollow(self $user)
    {
        if (!$user->getFollowers()->contains($this)) {
            return;
        }

        $user->getFollowers()->removeElement($this);
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @param ArrayCollection|User[] $followers
     */
    public function setFollowers($followers): void
    {
        $this->followers = $followers;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getFolloweds()
    {
        return $this->followed;
    }

    /**
     * @return ArrayCollection|Article[]
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * @param ArrayCollection|Article[] $favorites
     */
    public function setFavorites($favorites): void
    {
        $this->favorites = $favorites;
    }

    /**
     * @param Article $article
     *
     * @return bool
     */
    public function hasFavorite(Article $article)
    {
        return $this->favorites->contains($article);
    }

    /**
     * @param Article $article
     */
    public function addToFavorites(Article $article)
    {
        if ($this->favorites->contains($article)) {
            return;
        }

        $this->favorites->add($article);
    }

    /**
     * @param Article $article
     */
    public function removeFromFavorites(Article $article)
    {
        if (!$this->favorites->contains($article)) {
            return;
        }

        $this->favorites->removeElement($article);
    }
}
