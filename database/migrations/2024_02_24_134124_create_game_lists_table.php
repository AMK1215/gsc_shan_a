<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_type_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('game_id')->unique();
            $table->string('game_code')->unique();
            $table->string('game_name');
            $table->string('game_type');
            $table->string('image_url');
            $table->string('method');
            $table->boolean('is_h5_support');
            $table->string('maintenance');
            $table->string('game_lobby_config');
            $table->json('other_name')->nullable();
            $table->boolean('has_demo');
            $table->integer('sequence');
            $table->json('game_event')->nullable();
            $table->string('game_provide_code');
            $table->string('game_provide_name');
            $table->boolean('is_active');
            $table->bigInteger('click_count')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('hot_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_lists');
    }
};
