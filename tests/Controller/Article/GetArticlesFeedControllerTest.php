<?php

declare(strict_types=1);

namespace App\Tests\Controller\Article;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesFeedControllerTest.
 */
class GetArticlesFeedControllerTest extends WebTestCase
{
    public function testAsAnonymous(): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/articles/feed');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * @param string $user
     * @param int    $expectedCount
     *
     * @dataProvider get
     */
    public function testAsAuthenticated(string $user, int $expectedCount): void
    {
        $client = $this->createAuthenticatedApiClient($user);
        $client->request('GET', '/api/articles/feed');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('articles', $data);
        $this->assertArrayHasKey('articlesCount', $data);
        $this->assertSame($expectedCount, $data['articlesCount']);
    }

    public function get(): array
    {
        return [
            ['user1@conduit.tld', 23],
            ['user2@conduit.tld', 1],
            ['user3@conduit.tld', 0],
        ];
    }
}
