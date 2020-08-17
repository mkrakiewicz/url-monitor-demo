<?php

use App\Url;
use Illuminate\Database\Seeder;

class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Url::class)->createMany([
            ['url' => 'https://onet.pl'],
            ['url' => 'http://socialmention.com'],
            ['url' => 'http://test-redirects.137.software'],
            ['url' => 'https://google.com'],
        ]);

        factory(Url::class, 25)->create();
    }
}
