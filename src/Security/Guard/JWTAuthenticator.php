<?php

declare(strict_types=1);

namespace App\Security\Guard;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseJWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;

final class JWTAuthenticator extends BaseJWTAuthenticator
{
    protected function getTokenExtractor(): AuthorizationHeaderTokenExtractor
    {
        return new AuthorizationHeaderTokenExtractor('Token', 'Authorization');
    }
}
