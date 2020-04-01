<?php

declare(strict_types=1);

namespace App\Tests\Controller\Comment;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * CommentsDeleteControllerTest.
 */
final class DeleteCommentControllerTest extends WebTestCase
{
    public function testAsAnonymous(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('DELETE', '/api/articles/article-2/comments/1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsNotOwner(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('DELETE', '/api/articles/article-2/comments/1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testAsOwner(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('DELETE', '/api/articles/article-1/comments/2');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
