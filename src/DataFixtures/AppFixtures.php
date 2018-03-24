<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * AppFixtures.
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadTags($manager);
        $this->loadArticles($manager);
        $this->loadComments($manager);
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$reference, $email, $username, $password, $image, $bio, $follow]) {
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setImage($image);
            $user->setBio($bio);
            if ($follow) {
                $user->follow($this->getReference($follow));
            }
            $manager->persist($user);
            $this->addReference($reference, $user);
        }

        $manager->flush();
    }

    private function loadTags(ObjectManager $manager): void
    {
        foreach ($this->getTagData() as [$reference, $name]) {
            $tag = new Tag();
            $tag->setName($name);
            $manager->persist($tag);
            $this->addReference($reference, $tag);
        }

        $manager->flush();
    }

    private function loadArticles(ObjectManager $manager): void
    {
        foreach ($this->getArticleData() as [$reference, $title, $description, $body, $author, $tag]) {
            $article = new Article();
            $article->setAuthor($this->getReference($author));
            $article->setTitle($title);
            $article->setDescription($description);
            $article->setBody($body);
            $article->setTags([$this->getReference($tag)]);
            $manager->persist($article);
            $this->addReference($reference, $article);
        }

        $manager->flush();
    }

    private function loadComments(ObjectManager $manager): void
    {
        foreach ($this->getCommentData() as [$reference, $article, $author, $body]) {
            $comment = new Comment();
            $comment->setAuthor($this->getReference($author));
            $comment->setArticle($this->getReference($article));
            $comment->setBody($body);
            $manager->persist($comment);
            $this->addReference($reference, $comment);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['user-1', 'user1@realworld.tld', 'user1', 'password', 'https://www.shareicon.net/male-headset-young-man-person-support-avatar-106428', 'Bio', null],
            ['user-2', 'user2@realworld.tld', 'user2', 'password', null, null, 'user-1'],
        ];
    }

    private function getTagData(): array
    {
        return [
            ['tag-1', 'lorem'],
            ['tag-2', 'ipsum'],
        ];
    }

    private function getArticleData(): array
    {
        return [
            ['article-1', 'Article #1', 'Description #1', 'Body #1', 'user-1', 'tag-1'],
            ['article-2', 'Article #2', 'Description #2', 'Body #2', 'user-2', 'tag-2'],
        ];
    }

    private function getCommentData(): array
    {
        return [
            ['comment-1', 'article-1', 'user-2', 'Comment #1'],
            ['comment-2', 'article-2', 'user-1', 'Comment #2'],
        ];
    }
}
