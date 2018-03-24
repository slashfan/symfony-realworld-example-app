<?php

namespace App\Security\Voter;

use App\Entity\Comment;

/**
 * CommentVoter.
 */
class CommentVoter extends ArticleVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['OWNER'], true) && $subject instanceof Comment;
    }
}
