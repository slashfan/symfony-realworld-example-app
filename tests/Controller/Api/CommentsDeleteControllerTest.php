<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * CommentsDeleteControllerTest.
 */
class CommentsDeleteControllerTest extends WebTestCase
{
    public function testAsAnonymous()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('DELETE', '/api/articles/article-2/comments/1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsNotOwner()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('DELETE', '/api/articles/article-2/comments/1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAsOwner()
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('DELETE', '/api/articles/article-1/comments/2');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
