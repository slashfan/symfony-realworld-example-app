<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesPostControllerTest.
 */
class ArticlesPostControllerTest extends WebTestCase
{
    public function testResponse()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/articles', [], [], [], json_encode([
            'article' => [
                'title' => 'Article #3',
                'description' => 'Description #3',
                'body' => 'Body #3',
                'tags' => [],
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('article', $data);
    }
}
