<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseJWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;

/**
 * JWTAuthenticator.
 */
class JWTAuthenticator extends BaseJWTAuthenticator
{
    /**
     * {@inheritdoc}
     */
    protected function getTokenExtractor()
    {
        $chainExtractor = parent::getTokenExtractor();
        $chainExtractor->addExtractor(new AuthorizationHeaderTokenExtractor('Token', 'Authorization'));

        return $chainExtractor;
    }
}
