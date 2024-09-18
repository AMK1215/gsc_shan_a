<?php

use App\Enums\TransactionStatus;
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
        Schema::create('seamless_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('game_type_id')->nullable();
            $table->decimal('transaction_amount', 12)->nullable();
            $table->decimal('valid_amount', 12)->nullable();
            $table->string('operator_id', 20);
            $table->dateTime('request_date_time');
            $table->string('signature', 100);
            $table->string('player_id', 50);
            $table->string('currency', 5);
            $table->unsignedBigInteger('round_id');
            $table->unsignedBigInteger('bet_id');
            $table->decimal('bet_amount', 15, 4);
            $table->decimal('exchange_rate', 8, 4)->default('1.0000');
            $table->string('game_code');
            $table->dateTime('tran_date_time');
            $table->string('auth_token', 500)->nullable();
            $table->string('provider_time_zone', 50);
            $table->dateTime('provider_tran_dt');
            $table->decimal('old_balance', 15, 4)->nullable();
            $table->decimal('new_balance', 15, 4)->nullable();
            $table->integer('rollback_type')->nullable();
            $table->string('status')->default(TransactionStatus::Pending);
            $table->timestamps();
            // Add unique index on bet_id and rollback_type to prevent duplicate entries
            $table->unique(['bet_id', 'rollback_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seamless_transactions');
    }
};
