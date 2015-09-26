<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInitialTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('username', 20)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 60);
            $table->integer('status', false, true); // 0 Good Standing, 10 Banned
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('stories', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('users'); // Owner of the story.
            $table->string('title', 255); // Title of the story.
            $table->string('theme', 64); // Primary theme of the story.
            $table->boolean('hidden'); // Should the story be hidden?
            $table->integer('status'); // 1 : Invite mode, 2 : Write Mode, 3 : Finished
            $table->timestamps();

            $table->integer('current_writer', false, true); // ID of the current writer.

            // ===============================================================
            // Settings (Future update: offload to another table using hasOne)
            // ===============================================================

            // How are users able to join this story?
            // 1 : Creator invite only
            // 2 : Creators and those invited by the creator can invite others.
            // 3 : Anyone with access to the link may join, hidden from site search.
            // 4 : Anyone who can see the story on the site can join.
            $table->integer('invite_mode', false, true);

            // The maximum number of users to allow on this story
            // (creator can invite past this number, others cannot).
            // INT : Maximum number of writers to allow on the story.
            $table->integer('max_writers', false, true);

            // The maximum number of words allowed per user written segment..
            $table->integer('min_words_per_segment');

            // The minimum number of words allowed per user written segment.
            $table->integer('max_words_per_segment');

            // The write mode for the story.
            // 1 : Ordered writing, each writer waits for their turn (ordered by invite order).
            // 2 : After each segment is posted a random user is selected to post the next.
            // 3 : Writers may post in any order except after themselves.
            $table->integer('write_mode');
        });

        Schema::create('segments', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id'); // Author of this segment.
            $table->integer('story_id'); // The story this segment belongs to.
            $table->text('content'); // The content of this segment.
            $table->timestamps();
        });

        // Pivot Tables

        Schema::create('story_user', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('story_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('story_id')->references('id')->on('stories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Extra Data

            // Is this writer the one who started the story?
            $table->boolean('creator')->default(0);
            // Has the invite been accepted?
            $table->boolean('active')->default(0);
            // Request finish
            $table->boolean('request_finish')->default(0);
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        // Pivot Tables first as foreign keys prevent standard tables from droping
        Schema::drop('story_user');

        // Standard tables
        Schema::drop('segments');
        Schema::drop('stories');
        Schema::drop('users');
	}

}
