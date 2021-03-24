<?php

declare(strict_types=1);

namespace App\Tests\Controller\Article;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class UpdateArticleControllerTest extends WebTestCase
{
    public function testAsAnonymous(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('PUT', '/api/articles/article-2');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsNotOwner(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/articles/article-2');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testBadRequestAsOwner(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/articles/article-1', [], [], [], \json_encode([
            'article' => [
                'title' => '',
            ],
        ]));

        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $client->getResponse()->getStatusCode());
        $this->assertSame(
            [
                'title' => ['can\'t be blank'],
            ],
            \json_decode($client->getResponse()->getContent(), true)
        );
    }

    public function testAsOwner(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('PUT', '/api/articles/article-1', [], [], [], \json_encode([
            'article' => [
                'title' => 'Article #1B',
                'description' => 'Description #1B',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = \json_decode($response->getContent(), true);
        $this->assertArrayHasKey('article', $data);
        $this->assertArrayHasKey('title', $data['article']);
        $this->assertSame('Article #1B', $data['article']['title']);
        $this->assertArrayHasKey('description', $data['article']);
        $this->assertSame('Description #1B', $data['article']['description']);
        $this->assertArrayHasKey('body', $data['article']);
        $this->assertSame('Body #1', $data['article']['body']);
    }
}
