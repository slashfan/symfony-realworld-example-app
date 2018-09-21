<?php

declare(strict_types=1);

namespace App\Security\Guard;

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
        return new AuthorizationHeaderTokenExtractor('Token', 'Authorization');
    }
}
