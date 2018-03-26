<?php

namespace App\Tests\Controller\Article;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesDeleteControllerTest.
 */
class DeleteArticleControllerTest extends WebTestCase
{
    public function testAsAnonymous()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('DELETE', '/api/articles/article-1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsNotOwner()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('DELETE', '/api/articles/article-2');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAsOwner()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('DELETE', '/api/articles/article-1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
