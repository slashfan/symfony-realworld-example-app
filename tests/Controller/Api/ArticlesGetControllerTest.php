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
        $client = $this->createAuthenticatedApiClient();
        $client->request('GET', '/api/articles/article-1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('article', $data);

        $this->assertArrayHasKey('title', $data['article']);
        $this->assertSame('Article #1', $data['article']['title']);

        $this->assertArrayHasKey('slug', $data['article']);
        $this->assertSame('article-1', $data['article']['slug']);
    }
}
