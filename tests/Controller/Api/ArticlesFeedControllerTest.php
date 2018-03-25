<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesFeedControllerTest.
 */
class ArticlesFeedControllerTest extends WebTestCase
{
    public function testAsAnonymous()
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
    public function testAsAuthenticated(string $user, int $expectedCount)
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
            ['user1@realworld.tld', 0],
            ['user2@realworld.tld', 1],
        ];
    }
}
