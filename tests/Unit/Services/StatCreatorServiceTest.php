<?php

namespace Tests\Unit\Services;

use App\Services\Stats\StatCreatorService;
use App\Structs\StatCounter;
use PHPUnit\Framework\TestCase;

class StatCreatorServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StatCreatorService();
    }

    /**
     * @return void
     */
    public function testCreate()
    {
        $url = 'url.com';
        $counter = new StatCounter();
        $counter->addTime($url, 5.4);
        $counter->addRedirects($url, 1);
        $counter->setCompleted($url);
        $counter->setStatus($url, 200);
        $urlStatsContainer = $this->service->collectStats(['bad.com', $url], $counter);
        $urlStat = $urlStatsContainer->getStats($url);

        $this->assertEquals(1, $urlStat->getNumberOfRedirects());
        $this->assertEquals(5.4, $urlStat->getTotalTime());
        $this->assertNull($urlStatsContainer->getStats('bad.com'));
    }
}
