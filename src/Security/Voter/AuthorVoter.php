<?php

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
    protected function supports($attribute, $subject)
    {
        return 'AUTHOR' === $attribute && ($subject instanceof Article || $subject instanceof Comment);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Article|Comment $subject */
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $subject->getAuthor()->getId() === $user->getId();
    }
}
