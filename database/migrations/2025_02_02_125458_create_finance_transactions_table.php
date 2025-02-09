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
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('due_date');
            $table->date('transaction_date')->nullable();
            
            $table->decimal('value', 10, 2);
            $table->string('description')->nullable();
            $table->string('payment_method')->nullable();

            $table->foreignId('entity_id')->constrained('entities')->onDelete('cascade'); 
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_plan_id')->constrained('account_plans')->onDelete('cascade'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
