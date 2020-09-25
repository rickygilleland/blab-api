<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->unsigned()->index(); 
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->index(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_public')->default(0);
            $table->boolean('is_shared_with_organization')->default(0);
            $table->longtext('transcription')->default(null)->nullable();
            $table->string('path')->default(null)->nullable();
            $table->string('slug');
            $table->string('mime_type')->default(null)->nullable();
            $table->integer('duration')->default(null)->nullable();
            $table->boolean('processed')->default(0);
            $table->string('thumbnail_path')->default(null)->nullable();
            $table->longtext('temporary_url')->default(null)->nullable();
            $table->date('temporary_url_last_updated')->default(null)->nullable();
            $table->longtext('thumbnail_temporary_url')->default(null)->nullable();
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
        Schema::dropIfExists('attachments');
    }
}
