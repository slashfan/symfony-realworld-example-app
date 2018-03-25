<?php

namespace App\Tests\Controller\Api;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * ArticlesListControllerTest.
 */
class ArticlesListControllerTest extends WebTestCase
{
    public function testResponse()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/articles');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('articles', $data);
        $this->assertArrayHasKey('articlesCount', $data);
    }
}
