<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesPutControllerTest.
 */
class ArticlesPutControllerTest extends WebTestCase
{
    public function testNotOK()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/articles/article-2');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testOK()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/articles/article-1', [], [], [], json_encode([
            'article' => [
                'title' => 'Article #1B',
                'description' => 'Description #1B',
                'body' => 'Body #1B',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('article', $data);
        $this->assertArrayHasKey('title', $data['article']);
        $this->assertSame('Article #1B', $data['article']['title']);
        $this->assertArrayHasKey('description', $data['article']);
        $this->assertSame('Description #1B', $data['article']['description']);
        $this->assertArrayHasKey('body', $data['article']);
        $this->assertSame('Body #1B', $data['article']['body']);
    }
}
