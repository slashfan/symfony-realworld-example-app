<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * CommentPostControllerTest.
 */
class CommentPostControllerTest extends WebTestCase
{
    public function testResponse()
    {
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
