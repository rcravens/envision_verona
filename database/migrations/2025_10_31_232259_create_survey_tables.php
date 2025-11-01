<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * -----------------------------------------------------------
         *  SURVEY BLUEPRINTS (reusable definitions)
         * -----------------------------------------------------------
         */
        Schema::create( 'survey_blueprints', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'author_id' )->constrained( 'users' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->string( 'title' );
            $table->text( 'description' )->nullable();
            $table->boolean( 'is_active' )->default( true );
            $table->json( 'tags' )->nullable();
            $table->softDeletes();
            $table->timestamps();
        } );

        Schema::create( 'survey_blueprint_sections', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'survey_blueprint_id' )->constrained( 'survey_blueprints' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer( 'order' );
            $table->string( 'title' );
            $table->text( 'description' )->nullable();
            $table->softDeletes();
            $table->timestamps();
        } );

        Schema::create( 'survey_blueprint_questions', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'survey_blueprint_section_id' )->constrained( 'survey_blueprint_sections' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId( 'parent_id' )->nullable()->constrained( 'survey_blueprint_questions' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer( 'order' );
            $table->json( 'parent_answers' )->nullable();
            $table->string( 'question' );
            $table->text( 'details' )->nullable();
            $table->string( 'additional_info_url' )->nullable();
            $table->string( 'type' ); // Enum-backed in Eloquent
            $table->json( 'allowed_values' )->nullable();
            $table->boolean( 'is_answer_required' )->default( false );
            $table->softDeletes();
            $table->timestamps();
        } );

        /**
         * -----------------------------------------------------------
         *  SURVEYS (actual instances of blueprints)
         * -----------------------------------------------------------
         */
        Schema::create( 'surveys', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'author_id' )->constrained( 'users' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId( 'survey_blueprint_id' )->nullable()->constrained( 'survey_blueprints' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->string( 'title' );
            $table->text( 'description' )->nullable();
            $table->string( 'status' ); // Enum-backed in Eloquent
            $table->dateTime( 'status_at' );
            $table->ulid( 'hash' )->unique();
            $table->softDeletes();
            $table->timestamps();

            $table->index( 'status' );
            $table->index( 'survey_blueprint_id' );
        } );

        /**
         * -----------------------------------------------------------
         *  SURVEY SECTIONS (snapshot from blueprints)
         * -----------------------------------------------------------
         */
        Schema::create( 'survey_sections', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'survey_id' )->constrained( 'surveys' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer( 'order' );
            $table->string( 'title' );
            $table->text( 'description' )->nullable();
            $table->softDeletes();
            $table->timestamps();
        } );

        /**
         * -----------------------------------------------------------
         *  SURVEY QUESTIONS (snapshot from blueprint questions)
         * -----------------------------------------------------------
         */
        Schema::create( 'survey_questions', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'survey_section_id' )->constrained( 'survey_sections' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId( 'parent_id' )->nullable()->constrained( 'survey_questions' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer( 'order' );
            $table->json( 'parent_answers' )->nullable();
            $table->string( 'question' );
            $table->text( 'details' )->nullable();
            $table->string( 'additional_info_url' )->nullable();
            $table->string( 'type' ); // Enum-backed in Eloquent
            $table->json( 'allowed_values' )->nullable();
            $table->boolean( 'is_answer_required' )->default( false );
            $table->softDeletes();
            $table->timestamps();
        } );

        /**
         * -----------------------------------------------------------
         *  SURVEY TARGETS (recipients/respondents)
         * -----------------------------------------------------------
         */
        Schema::create( 'survey_targets', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'survey_id' )->constrained( 'surveys' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId( 'user_id' )->nullable()->constrained( 'users' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->ulid( 'hash' )->unique();
            $table->string( 'first_name' );
            $table->string( 'last_name' );
            $table->string( 'email' );
            $table->boolean( 'is_completed' )->default( false );
            $table->dateTime( 'last_viewed_at' )->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index( 'is_completed' );
        } );

        /**
         * -----------------------------------------------------------
         *  SURVEY ANSWERS (responses)
         * -----------------------------------------------------------
         */
        Schema::create( 'survey_answers', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'survey_target_id' )->constrained( 'survey_targets' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId( 'survey_question_id' )->constrained( 'survey_questions' )->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime( 'answered_at' )->nullable();
            $table->string( 'answer', 2048 )->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique( [
                                'survey_target_id',
                                'survey_question_id'
                            ] );
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists( 'survey_answers' );
        Schema::dropIfExists( 'survey_targets' );
        Schema::dropIfExists( 'survey_questions' );
        Schema::dropIfExists( 'survey_sections' );
        Schema::dropIfExists( 'surveys' );
        Schema::dropIfExists( 'survey_blueprint_questions' );
        Schema::dropIfExists( 'survey_blueprint_sections' );
        Schema::dropIfExists( 'survey_blueprints' );
    }
};
