<?php

declare(strict_types=1);

namespace App\Tests\Controller\User;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class UpdateUserControllerTest extends WebTestCase
{
    public function testAsAnonymous(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('PUT', '/api/user');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testEmptyRequestAsAuthenticated(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/user');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testBadRequestAsAuthenticated(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/user', [], [], [], json_encode([
            'user' => [
                'email' => '',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'email' => ['can\'t be blank'],
            ],
            json_decode($response->getContent(), true)
        );
    }

    public function testAsAuthenticated(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/user', [], [], [], json_encode([
            'user' => [
                'email' => 'user1001@conduit.tld',
                'username' => 'user1001',
                'password' => 'password',
                'image' => 'http://user1001.tld',
                'bio' => 'Bio #1001',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user', $data);
        $this->assertSame('user1001@conduit.tld', $data['user']['email']);
        $this->assertSame('user1001', $data['user']['username']);
    }
}
