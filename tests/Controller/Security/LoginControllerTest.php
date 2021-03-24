<?php

declare(strict_types=1);

namespace App\Tests\Controller\Security;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class LoginControllerTest extends WebTestCase
{
    public function testBadRequestResponse(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/users/login');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testResponse(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/users/login', [], [], [], \json_encode([
            'user' => [
                'email' => 'user1@conduit.tld',
                'password' => 'password',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = \json_decode($response->getContent(), true);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('email', $data['user']);
        $this->assertSame('user1@conduit.tld', $data['user']['email']);
        $this->assertArrayHasKey('token', $data['user']);
    }
}
