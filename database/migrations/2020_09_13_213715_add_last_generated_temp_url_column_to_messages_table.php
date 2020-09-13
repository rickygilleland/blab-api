<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastGeneratedTempUrlColumnToMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('attachment_temporary_url')->default(null)->nullable();
            $table->date('attachment_temporary_url_last_updated')->default(null)->nullable();
            $table->string('attachment_thumbnail_temporary_url')->default(null)->nullable();
            $table->date('attachment_thumbnail_temporary_url_last_updated')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            //
        });
    }
}
