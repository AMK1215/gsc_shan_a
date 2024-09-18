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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('payable');
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 64, 0);
            $table->string('transaction_name');
            $table->enum('type', ['deposit', 'withdraw']);
            $table->json('meta')->nullable();
            $table->uuid('uuid')->unique()->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id'], 'payable_type_payable_id_ind')->nullable();
            $table->index(['payable_type', 'payable_id', 'type'], 'payable_type_ind')->nullable();
            //$table->index(['payable_type', 'payable_id', 'confirmed'], 'payable_confirmed_ind');
            //$table->index(['payable_type', 'payable_id', 'type', 'confirmed'], 'payable_type_confirmed_ind');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
