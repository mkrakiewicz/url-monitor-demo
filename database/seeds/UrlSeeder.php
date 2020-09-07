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
            ['url' => 'https://google.com'],
        ])->each(function (array $urlArr) use ($user) {
            /** @var Url $url */
            $url = factory(Url::class)->state('demo')->create($urlArr);
            $url->users()->attach($user);
        });

        $urlIds = factory(Url::class, 200)->state('demo')->create()->pluck('id');
        User::limit(50)->each(function (User $user) use ($urlIds) {
            $user->urls()->attach($urlIds->random(10));
        });
    }
}
