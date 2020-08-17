<?php

namespace Tests\Feature\Api\Monitoring;

use App\Services\Stats\Bulk\BulkHttpStatsFetcherService;
use App\Structs\UrlStat;
use App\Structs\UrlStatsBuffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AddUrlsToMonitorTest extends TestCase
{
    use RefreshDatabase;
    /** @var \App\Services\Stats\Bulk\BulkHttpStatsFetcherService|\PHPUnit\Framework\MockObject\MockObject */
    private $urlMonitorMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlMonitorMock = $this->createMock(BulkHttpStatsFetcherService::class);
        $this->app->bind(BulkHttpStatsFetcherService::class, function () {
            return $this->urlMonitorMock;
        });
    }

    public function testAddOneMonitor()
    {
        $url = 'some.url.com';
        $response = $this->post('/monitors', ['items' => [$url]]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('urls', ['url' => $url]);
    }

    public function testAddMultipleMonitors()
    {
        $urls = [];
        for ($i = 0; $i < 50; $i++) {
            $urls[] = "some.url$i.com";
        }

        $response = $this->post('/monitors', ['items' => $urls]);

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
        $results->addStat($existingUrl, new UrlStat($existingUrl, 5.0, 10));

        $this->urlMonitorMock->method('getBulkStats')->willReturn($results);
        $urls = [$existingUrl, $notExistingUrl];

        /** @var Response|TestResponse $response */
        $response = $this->post('/monitors?stats=1', ['items' => $urls]);

        $response->assertStatus(200);
        $headerData = $response->headers->get(config('url-monitor.store.stats-header-name'));

        $this->assertEquals([$existingUrl => ['totalTime' => 5, 'redirectsCount' => 10], $notExistingUrl => null],
            json_decode($headerData, true));
        $this->assertDatabaseHas('urls', ['url' => $urls[0]]);
        $this->assertDatabaseHas('urls', ['url' => $urls[1]]);
    }
}
