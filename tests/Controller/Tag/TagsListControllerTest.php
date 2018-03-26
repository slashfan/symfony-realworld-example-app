<?php

namespace App\Tests\Controller\Tag;

use App\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * TagsListControllerTest.
 */
class TagsListControllerTest extends WebTestCase
{
    public function testResponse()
    {
        $client = $this->createAnonymousApiClient();
        $client->request('GET', '/api/tags');

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('tags', $data);
        $this->assertCount(2, $data['tags']);
    }
}
