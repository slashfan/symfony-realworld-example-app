<?php

declare(strict_types=1);

namespace App\Tests\Controller\Comment;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * CommentPostControllerTest.
 */
class CreateCommentControllerTest extends WebTestCase
{
    public function testAsAnonymous(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/articles/article-2/comments');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsAuthenticated(): void
    {
        // empty request

        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/articles/article-2/comments');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'body' => ['can\'t be blank'],
            ],
            \json_decode($response->getContent(), true)
        );

        // invalid request

        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/articles/article-2/comments', [], [], [], \json_encode([
            'comment' => [
                'body' => '',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame(
            [
                'body' => ['can\'t be blank'],
            ],
            \json_decode($response->getContent(), true)
        );

        // valid request

        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/articles/article-2/comments', [], [], [], \json_encode([
            'comment' => [
                'body' => 'Comment #3 on article #2 by user #1',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        $data = \json_decode($response->getContent(), true);
        $this->assertArrayHasKey('comment', $data);
        $this->assertArrayHasKey('body', $data['comment']);
        $this->assertSame('Comment #3 on article #2 by user #1', $data['comment']['body']);
    }
}
