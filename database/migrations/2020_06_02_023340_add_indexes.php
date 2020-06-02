<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->index(['email', 'created_at', 'updated_at']);

            $table->bigInteger('team_id')->unsigned()->index()->change(); 
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');

            $table->bigInteger('organization_id')->unsigned()->index()->change(); 
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

        });

        Schema::table('login_codes', function (Blueprint $table) {
            $table->index(['code', 'created_at', 'updated_at']);
            
            $table->bigInteger('user_id')->unsigned()->index()->change(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->index(['slug', 'email_domain']);
        });

        Schema::table('role_user', function (Blueprint $table) {

            $table->bigInteger('role_id')->unsigned()->index()->change(); 
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->index()->change(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('organization_id')->unsigned()->index()->change(); 
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

        });

        Schema::table('roles', function (Blueprint $table) {
            $table->index(['name']);
        });

        Schema::table('room_user', function (Blueprint $table) {

            $table->bigInteger('room_id')->unsigned()->index()->change(); 
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            
            $table->bigInteger('user_id')->unsigned()->index()->change(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->index(['type', 'slug', 'channel_id']);

            $table->bigInteger('team_id')->unsigned()->index()->change(); 
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');

            $table->bigInteger('organization_id')->unsigned()->index()->change(); 
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->bigInteger('server_id')->unsigned()->index()->change(); 
            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');

        });

        Schema::table('servers', function (Blueprint $table) {
            $table->index(['hostname', 'location', 'is_active']);
        });

        Schema::table('team_user', function (Blueprint $table) {

            $table->bigInteger('team_id')->unsigned()->index()->change(); 
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->index()->change(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('teams', function (Blueprint $table) {
            $table->index(['is_default', 'slug']);

            $table->bigInteger('organization_id')->unsigned()->index()->change(); 
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['created_at', 'updated_at']);

            $table->bigInteger('organization_id')->unsigned()->index()->change(); 
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

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
}
