<?php

namespace Tests\Unit\Structs;

use App\Structs\StatCounter;
use PHPUnit\Framework\TestCase;

class StatCounterTest extends TestCase
{
    private $counter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->counter = new StatCounter();
    }

    public function testAddTime()
    {
        $url = 'url.com';
        $this->counter->addTime($url, 5.4);
        $this->counter->addTime($url, 5.4);
        $this->assertEquals(10.8, $this->counter->getTime($url));
    }

    public function testAddRedirects()
    {
        $url = 'url.com';
        $this->counter->addRedirects($url, 1);
        $this->counter->addRedirects($url, 5);
        $this->assertEquals(6, $this->counter->getRedirects($url));
    }
}
