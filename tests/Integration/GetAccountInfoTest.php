<?php

namespace Messerli90\Hunterio\Tests\Integration;

use Hunter;
use Illuminate\Support\Facades\Http;
use Messerli90\Hunterio\Tests\TestCase;

class GetAccountInfoTest extends TestCase
{
    /** @test */
    public function it_returns_a_response()
    {
        $this->app['config']->set('services.hunter.key', 'apikey');

        Http::fake(function () {
            return Http::response(file_get_contents(__DIR__ . '/../mocks/account-info.json'));
        });

        $response = Hunter::account();

        $this->assertArrayHasKey('data', $response);
    }
}
