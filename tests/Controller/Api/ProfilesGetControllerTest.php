<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProfilesGetControllerTest.
 */
class ProfilesGetControllerTest extends WebTestCase
{
    public function testResponse()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/profiles/user1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('profile', $data);
    }
}
