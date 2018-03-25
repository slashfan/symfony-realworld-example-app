<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesListControllerTest.
 */
class ArticlesListControllerTest extends WebTestCase
{
    /**
     * @param string $query
     * @param int    $expectedCount
     *
     * @dataProvider getQueryParams
     */
    public function testResponse(string $query, int $expectedCount)
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/articles'.$query);

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('articles', $data);
        $this->assertArrayHasKey('articlesCount', $data);
        $this->assertSame($expectedCount, $data['articlesCount']);
    }

    public function getQueryParams(): array
    {
        return [
            ['', 2],
            ['?tag=lorem', 1],
            ['?author=user2', 1],
            ['?favorited=user1', 1],
            ['?tag=lorem&author=user2&favorited=user1', 0],
        ];
    }
}
