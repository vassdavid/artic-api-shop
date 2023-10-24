<?php

namespace App\Tests\Integration\Controller;

use App\Controller\ArtworkController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(ArtworkController::class)]
#[CoversFunction("showAction")]
class ShowActionTest extends WebTestCase
{
    public function testShowAction(): void
    {
        $client = static::createClient();

        // Send a GET request to the show action
        $client->request('GET', '/artwork/show?id=22');

        // Get the response
        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}
