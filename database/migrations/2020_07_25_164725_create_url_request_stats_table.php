<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlRequestStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_request_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('url_id');
            $table->unsignedBigInteger('url_request_id');
            $table->unsignedBigInteger('user_id');

            $table->float('total_loading_time');
            $table->unsignedInteger('redirects_count');
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['url_id']);
            $table->index(['url_request_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_request_stats');
    }
}
