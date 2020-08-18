<?php

use App\Url;
use App\User;
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
        $user = User::where(['email' => 'user@test.com'])->firstOrFail();
        collect([
            ['url' => 'https://onet.pl',],
            ['url' => 'http://socialmention.com'],
            ['url' => 'http://test-redirects.137.software'],
            ['url' => 'https://google.com'],
        ])->each(function (array $urlArr) use ($user) {
            $url = factory(Url::class)->state('demo')->make($urlArr);
            $url->user()->associate($user);
            $url->save();
        });

        factory(Url::class, 25)->state('demo')->create();
    }
}
