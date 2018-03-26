<?php

namespace App\Tests\Controller\Article;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesPostControllerTest.
 */
class CreateArticleControllerTest extends WebTestCase
{
    public function testAsAnonymous()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/articles');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testBadRequestAsAuthenticated()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/articles');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testAsAuthenticated()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/articles', [], [], [], json_encode([
            'article' => [
                'title' => 'Article #3',
                'description' => 'Description #3',
                'body' => 'Body #3',
                'tagList' => [
                    ' lorem',
                    'ipsum',
                    'dolor',
                ],
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('article', $data);
        $this->assertArrayHasKey('title', $data['article']);
        $this->assertArrayHasKey('description', $data['article']);
        $this->assertArrayHasKey('body', $data['article']);
        $this->assertArrayHasKey('tagList', $data['article']);
        $this->assertSame('Article #3', $data['article']['title']);
        $this->assertSame('Description #3', $data['article']['description']);
        $this->assertSame('Body #3', $data['article']['body']);
        $this->assertCount(3, $data['article']['tagList']);
        $this->assertContains('lorem', $data['article']['tagList']);
        $this->assertContains('ipsum', $data['article']['tagList']);
        $this->assertContains('dolor', $data['article']['tagList']);
    }
}
