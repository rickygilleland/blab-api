<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string("hostname");
            $table->string("location");
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });

        DB::table('servers')->insert([
            'hostname' => 'streamer-us-west-1.blab.to',
            'location' => 'us-west',
            'is_active' => true
        ]);

        DB::table('servers')->insert([
            'hostname' => 'streamer-us-west-2.blab.to',
            'location' => 'us-west',
            'is_active' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
