<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesGetControllerTest.
 */
class ArticlesGetControllerTest extends WebTestCase
{
    public function testResponse()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/articles/article-1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('article', $data);
    }
}
