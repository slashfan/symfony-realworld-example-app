<?php

namespace App\Tests\Controller\Article;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesPutControllerTest.
 */
class UpdateArticleControllerTest extends WebTestCase
{
    public function testAsAnonymous()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('PUT', '/api/articles/article-2');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsNotOwner()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/articles/article-2');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testBadRequestAsOwner()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/articles/article-1', [], [], [], json_encode([
            'article' => [
                'title' => '',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testAsOwner()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/articles/article-1', [], [], [], json_encode([
            'article' => [
                'title' => 'Article #1B',
                'description' => 'Description #1B',
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
        $this->assertSame('Body #1', $data['article']['body']);
    }
}
