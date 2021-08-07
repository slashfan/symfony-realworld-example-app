<?php

declare(strict_types=1);

namespace App\Tests\Controller\Comment;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetCommentsListControllerTest extends WebTestCase
{
    public function testResponse(): void
    {
        $client = $this->createAuthenticatedApiClient();
        $client->request('GET', '/api/articles/article-1/comments');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('comments', $data);
        $this->assertCount(1, $data['comments']);
    }
}
