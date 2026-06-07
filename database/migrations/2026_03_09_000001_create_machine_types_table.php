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
        Schema::create('machine_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('category_code', 20);   // e.g. EEC 10-01
            $table->string('description', 200);     // e.g. Motor Cycle
            $table->string('eec_number', 30);       // e.g. EEC 10-01-001
            $table->timestamps();

            $table->unique(['category_id', 'category_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_types');
    }
};
