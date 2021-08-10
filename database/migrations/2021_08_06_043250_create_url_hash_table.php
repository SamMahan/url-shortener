<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlHashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_hashes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('hash_key')->index()->unique()->nullable();
            $table->string('url')->unique();
            $table->integer('times_accessed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_hashes');
    }
}
