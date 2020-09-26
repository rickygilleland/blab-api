<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentLibraryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachment_library_item', function (Blueprint $table) {
            $table->bigInteger('attachment_id')->unsigned()->index(); 
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('cascade');
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
        Schema::dropIfExists('attachment_library_item');
    }
}
