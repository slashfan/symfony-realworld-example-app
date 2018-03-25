<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesFeedControllerTest.
 */
class ArticlesFeedControllerTest extends WebTestCase
{
    public function testAsAnonymous()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/articles/feed');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsAuthenticated()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('GET', '/api/articles/feed');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('articles', $data);
        $this->assertArrayHasKey('articlesCount', $data);
    }
}
