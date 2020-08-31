<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');
        });
        
        Schema::table('messages', function (Blueprint $table) {
            $table->bigInteger('room_id')->unsigned()->index()->nullable()->default(null); 
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->longText('text')->nullable()->default(null)->change();
            $table->string('attachment_url')->nullable()->default(null);
            $table->boolean('is_public')->default(false);
            $table->string('slug');
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
