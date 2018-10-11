<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * ArticleVoter.
 */
class AuthorVoter extends Voter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return $attribute === 'AUTHOR' && ($subject instanceof Article || $subject instanceof Comment);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var Article|Comment $subject */
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $subject->getAuthor()->getId() === $user->getId();
    }
}
