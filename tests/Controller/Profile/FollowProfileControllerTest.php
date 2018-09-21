<?php

declare(strict_types=1);

namespace App\Tests\Controller\Profile;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProfilesFollowControllerTest.
 */
class FollowProfileControllerTest extends WebTestCase
{
    public function testAsAnonymous(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/profiles/user2/follow');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsAuthenticated(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/profiles/user2/follow');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('profile', $data);
    }
}
