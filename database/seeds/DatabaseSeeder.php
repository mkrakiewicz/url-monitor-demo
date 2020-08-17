<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $this->call(UrlSeeder::class);
        $this->call(UrlRequestSeeder::class);
        $this->call(UrlRequestStatSeeder::class);
    }
}
