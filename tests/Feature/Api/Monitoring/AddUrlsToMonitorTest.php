<?php

namespace Tests\Feature\Api\Monitoring;

use App\Services\Stats\Bulk\BulkHttpStatsFetcherService;
use App\Structs\UrlStat;
use App\Structs\UrlStatsBuffer;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AddUrlsToMonitorTest extends TestCase
{
    use RefreshDatabase;
    /** @var \App\Services\Stats\Bulk\BulkHttpStatsFetcherService|\PHPUnit\Framework\MockObject\MockObject */
    private $urlMonitorMock;
    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlMonitorMock = $this->createMock(BulkHttpStatsFetcherService::class);
        $this->app->bind(BulkHttpStatsFetcherService::class, function () {
            return $this->urlMonitorMock;
        });
        $this->user = factory(User::class)->create();
        $this->withoutExceptionHandling();
    }

    public function testAddOneMonitor()
    {
        $url = 'some.url.com';
        $response = $this
            ->actingAs($this->user)
            ->post(route('api.user.bulk-monitor.store', [$this->user]), ['items' => [$url]]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('urls', ['url' => $url]);
    }

    public function testAddMultipleMonitors()
    {
        $urls = [];
        for ($i = 0; $i < 50; $i++) {
            $urls[] = "some.url$i.com";
        }

        $response = $this
            ->actingAs($this->user)
            ->post(route('api.user.bulk-monitor.store', [$this->user]), ['items' => $urls]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('urls', ['url' => $urls[0]]);
        $this->assertDatabaseHas('urls', ['url' => $urls[49]]);
        $this->assertDatabaseCount('urls', 50);
    }

    public function testAddWithStats()
    {
        $existingUrl = 'some.url.com';
        $notExistingUrl = 'not-existing.com';
        $results = new UrlStatsBuffer();
        $results->addStat($existingUrl, new UrlStat($existingUrl, 5.0, 10, 200));

        $this->urlMonitorMock->method('getBulkStats')->willReturn($results);
        $urls = [$existingUrl, $notExistingUrl];

        /** @var Response|TestResponse $response */
        $response = $this
            ->actingAs($this->user)
            ->post(
                route('api.user.bulk-monitor.store', [$this->user, 'stats' => true]
                ), ['items' => $urls]
            );

        $response->assertStatus(200);

        $this->assertDatabaseHas('urls', ['url' => $urls[0]]);
        $this->assertDatabaseHas('urls', ['url' => $urls[1]]);
    }
}
