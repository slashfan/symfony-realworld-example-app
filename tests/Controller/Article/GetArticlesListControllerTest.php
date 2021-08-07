<?php

declare(strict_types=1);

namespace App\Tests\Controller\Article;

use App\Tests\TestCase\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetArticlesListControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideResponseCases
     */
    public function testResponse(string $query, int $expectedCount): void
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/articles' . $query);

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('articles', $data);
        $this->assertArrayHasKey('articlesCount', $data);
        $this->assertSame($expectedCount, $data['articlesCount']);
    }

    public function provideResponseCases(): iterable
    {
        yield ['', 25];
        yield ['?tag=lorem', 1];
        yield ['?tag=ipsum', 24];
        yield ['?author=user2', 1];
        yield ['?favorited=user1', 1];
        yield ['?tag=lorem&author=user2&favorited=user1', 0];
    }
}
