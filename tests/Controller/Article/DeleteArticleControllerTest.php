<?php

declare(strict_types=1);

namespace App\Tests\Controller\Article;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesDeleteControllerTest.
 */
class DeleteArticleControllerTest extends WebTestCase
{
    public function testAsAnonymous(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('DELETE', '/api/articles/article-1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsNotOwner(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('DELETE', '/api/articles/article-2');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAsOwner(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('DELETE', '/api/articles/article-1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
