<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateAttachmentMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachment_message', function (Blueprint $table) {
            $table->bigInteger('attachment_id')->unsigned()->index(); 
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('cascade');
            $table->bigInteger('message_id')->unsigned()->index(); 
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
            $table->timestamps();
        });

        //migrate the existing message attachments
        $current_messages = \App\Message::get();

        foreach ($current_messages as $message) {
            $attachment = new \App\Attachment();

            $attachment->user_id = $message->user_id;
            $attachment->organization_id = $message->organization_id;
            $attachment->path = $message->attachment_path;
            $attachment->is_public = $message->is_public;
            $attachment->processed = $message->attachment_processed;
            $attachment->duration = $message->attachment_duration;
            $attachment->mime_type = $message->attachment_mime_type;
            $attachment->thumbnail_path = $message->attachment_thumbnail_path;
            $attachment->temporary_url = $message->attachment_temporary_url;
            $attachment->temporary_url_last_updated = $message->attachment_temporary_url_last_updated;
            $attachment->thumbnail_temporary_url = $message->attachment_thumbnail_temporary_url;
            $attachment->slug = Str::random(12);
            $attachment->created_at = $message->created_at;
            $attachment->updated_at = $message->updated_at;
            $attachment->save();

            $message->attachments()->attach($attachment);
        }

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('attachment_path');
            $table->dropColumn('attachment_mime_type');
            $table->dropColumn('attachment_processed');
            $table->dropColumn('attachment_duration');
            $table->dropColumn('attachment_thumbnail_path');
            $table->dropColumn('attachment_temporary_url');
            $table->dropColumn('attachment_temporary_url_last_updated');
            $table->dropColumn('attachment_thumbnail_temporary_url');
            $table->dropColumn('attachment_thumbnail_temporary_url_last_updated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachment_message');
    }
}
