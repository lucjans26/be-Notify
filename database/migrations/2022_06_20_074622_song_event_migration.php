<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_events', function (Blueprint $table) {
            $table->id();
            $table->integer('song_id');
            $table->string('action_type');
            $table->string('name');
            $table->string('genre');
            $table->integer('album_id')->nullable();
            $table->string('resourceLocation')->nullable();
            $table->date('releaseDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
