<?php

declare(strict_types=1);

namespace App\Tests\Controller\Article;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesGetControllerTest.
 */
class GetOneArticleControllerTest extends WebTestCase
{
    public function testResponse(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/articles/article-1');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = \json_decode($response->getContent(), true);
        $this->assertArrayHasKey('article', $data);
    }
}
