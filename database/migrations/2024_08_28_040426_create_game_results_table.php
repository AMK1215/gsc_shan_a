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
        Schema::create('game_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User_id
            //$table->unsignedBigInteger('agent_id')->nullable();
            $table->string('operator_id', 20); // OperatorId
            $table->dateTime('request_date_time'); // RequestDateTime
            $table->string('signature', 50); // Signature
            $table->string('player_id', 50); // PlayerId
            $table->string('currency', 5); // Currency
            $table->unsignedBigInteger('result_id')->unique(); // ResultId
            $table->unsignedBigInteger('bet_id'); // BetId
            $table->unsignedBigInteger('round_id'); // RoundId
            $table->string('game_code', 50); // GameCode
            $table->string('game_type', 50); // GameType
            $table->string('game_name', 50); // GameName
            $table->integer('result_type'); // ResultType
            $table->decimal('bet_amount', 15, 4); // BetAmount
            $table->decimal('valid_bet_amount', 15, 4); // ValidBetAmount
            $table->decimal('payout', 15, 4); // Payout
            $table->decimal('win_lose', 15, 4); // WinLose
            $table->decimal('exchange_rate', 8, 4); // ExchangeRate
            $table->dateTime('tran_date_time'); // TranDateTime
            $table->string('provider_time_zone', 50); // ProviderTimeZone
            $table->dateTime('provider_tran_dt'); // ProviderTranDt
            $table->integer('round_type'); // RoundType
            $table->timestamps();
            //$table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_results');
    }
};
