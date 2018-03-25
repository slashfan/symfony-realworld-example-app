<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * CommentPostControllerTest.
 */
class CommentPostControllerTest extends WebTestCase
{
    public function testAsAnonymous()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('POST', '/api/articles/article-2/comments');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testAsAuthenticated()
    {
        // invalid request

        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/articles/article-2/comments');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        // invalid request

        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/articles/article-2/comments', [], [], [], json_encode([
            'comment' => [],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        // valid request

        $client = $this->createAuthenticatedApiClient();
        $client->request('POST', '/api/articles/article-2/comments', [], [], [], json_encode([
            'comment' => [
                'body' => 'Comment #3 on article #2 by user #1',
            ],
        ]));

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('comment', $data);
        $this->assertArrayHasKey('body', $data['comment']);
        $this->assertSame('Comment #3 on article #2 by user #1', $data['comment']['body']);
    }
}
