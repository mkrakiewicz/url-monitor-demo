<?php

namespace Tests\Feature\Api\Monitoring;

use App\Services\Stats\Bulk\BulkHttpStatsFetcherService;
use App\Url;
use App\UrlRequest;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowUrlMonitorTest extends TestCase
{
    use RefreshDatabase;
    /** @var \App\Services\Stats\Bulk\BulkHttpStatsFetcherService|\PHPUnit\Framework\MockObject\MockObject */
    private $urlMonitorMock;
    /** @var User */
    private $user;
    /** @var Url */
    private $url;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlMonitorMock = $this->createMock(BulkHttpStatsFetcherService::class);
        $this->app->bind(BulkHttpStatsFetcherService::class, function () {
            return $this->urlMonitorMock;
        });

        $this->url = factory(Url::class)->create();
        $this->user = factory(User::class)->create();
        $this->user->urls()->attach($this->url->id);

        $this->withoutExceptionHandling();
    }

    public function testShowNoStats()
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('api.user.bulk-monitor.show', [$this->user, $this->url]));

        $response->assertStatus(200);
        $response->assertJson(['url' => ['id' => $this->url->id]]);
        $response->assertJsonCount(0, 'requests');
    }

    public function testShowStats()
    {
        factory(UrlRequest::class, 3)->create(['url_id' => $this->url->id]);
        $response = $this
            ->actingAs($this->user)
            ->get(route('api.user.bulk-monitor.show', [$this->user, $this->url]));

        $response->assertStatus(200);
        $response->assertJson(['url' => ['id' => $this->url->id]]);
        $response->assertJsonCount(3, 'requests');
    }

    public function testShowCorrectStats()
    {
        $otherUrl = factory(Url::class)->create();
        factory(UrlRequest::class, 3)->create(['url_id' => $otherUrl->id]);
        factory(UrlRequest::class, 3)->create(['url_id' => $this->url->id]);

        $response = $this
            ->actingAs($this->user)
            ->get(route('api.user.bulk-monitor.show', [$this->user, $this->url]));

        $response->assertStatus(200);
        $response->assertJson(['url' => ['id' => $this->url->id]]);
        $response->assertJsonCount(3, 'requests');
    }
}
