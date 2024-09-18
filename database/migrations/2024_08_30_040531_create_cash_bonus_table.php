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
        Schema::create('cash_bonus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User_id
            $table->unsignedBigInteger('tran_id')->unique(); // TranId
            $table->string('bonus_id', 50); // BonusId
            $table->string('bonus_name', 100); // BonusName
            $table->string('result', 255); // Result
            $table->string('currency', 5); // Currency
            $table->decimal('exchange_rate', 10, 2); // ExchangeRate
            $table->decimal('payout', 15, 4); // Payout
            $table->string('player_id', 50); // PlayerId
            $table->dateTime('tran_date_time'); // TranDateTime
            $table->string('provider_time_zone', 50); // ProviderTimeZone
            $table->dateTime('provider_tran_dt'); // ProviderTranDt
            $table->string('operator_id', 20); // OperatorId
            $table->dateTime('request_date_time'); // RequestDateTime
            $table->string('signature', 100); // Signature
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_bonus');
    }
};
