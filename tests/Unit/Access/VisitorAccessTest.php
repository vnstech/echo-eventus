<?php

namespace Tests\Unit\Access;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class VisitorAccessTest extends TestCase
{
    private Client $client;

    public function setup(): void
    {
        parent::setUp();
        $this->client = new Client([
            'allow_redirects' => false, // Disable following redirects
            'base_uri' => 'http://web:8080'
        ]);
    }

    public function test_access_home_route(): void
    {
        $response = $this->client->get('/');

        $this->assertEquals(200, $response->getStatusCode());
    }
}
