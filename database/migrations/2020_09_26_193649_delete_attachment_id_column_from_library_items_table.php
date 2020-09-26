<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteAttachmentIdColumnFromLibraryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('library_items', function (Blueprint $table) {
            $table->dropForeign('library_items_attachment_id_foreign');
            $table->dropColumn('attachment_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('library_items', function (Blueprint $table) {
            //
        });
    }
}
