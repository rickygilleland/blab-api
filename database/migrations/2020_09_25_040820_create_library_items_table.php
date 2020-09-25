<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibraryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attachment_id')->unsigned()->index(); 
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('cascade');
            $table->bigInteger('created_by')->unsigned()->index(); 
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('library_items');
    }
}
