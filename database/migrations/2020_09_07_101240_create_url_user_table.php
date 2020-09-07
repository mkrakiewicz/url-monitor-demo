<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_user', function (Blueprint $table) {
            $table->unsignedBigInteger('url_id');
            $table->unsignedBigInteger('user_id');

            $table->index(['user_id', 'url_id']);
            $table->index(['url_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_user');
    }
}
