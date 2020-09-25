<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibraryItemUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_item_user', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->index(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('library_item_id')->unsigned()->index(); 
            $table->foreign('library_item_id')->references('id')->on('library_items')->onDelete('cascade');
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
        Schema::dropIfExists('library_item_user');
    }
}
