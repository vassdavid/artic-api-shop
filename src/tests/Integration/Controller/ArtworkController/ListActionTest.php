<?php

namespace App\Tests\Integration\Controller;

use App\Controller\ArtworkController;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(ArtworkController::class)]
class ListActionTest extends WebTestCase
{
    public function testListAction(): void
    {
        $client = static::createClient();

        // Send a GET request to the show action
        $client->request('GET', '/artwork/list?limit=20');

        // Get the response
        $response = $client->getResponse();

        // Perform assertions based on the expected behavior and results
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}
