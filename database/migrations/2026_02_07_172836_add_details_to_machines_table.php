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
        Schema::table('machines', function (Blueprint $table) {
            if (!Schema::hasColumn('machines', 'machine_type')) $table->string('machine_type')->nullable()->after('category_id');
            if (!Schema::hasColumn('machines', 'description')) $table->text('description')->nullable()->after('machine_name');
            if (!Schema::hasColumn('machines', 'model')) $table->string('model')->nullable()->after('machine_type');
            if (!Schema::hasColumn('machines', 'machine_group')) $table->string('machine_group')->nullable()->after('model');
            if (!Schema::hasColumn('machines', 'engine_type')) $table->string('engine_type')->nullable()->after('machine_group');
            if (!Schema::hasColumn('machines', 'engine_serial_number')) $table->string('engine_serial_number')->nullable()->after('engine_type');
            if (!Schema::hasColumn('machines', 'plate_number')) $table->string('plate_number')->nullable()->after('engine_serial_number');
            if (!Schema::hasColumn('machines', 'power')) $table->string('power')->nullable()->after('plate_number'); // e.g., "100Kw/134HP"
            if (!Schema::hasColumn('machines', 'weight')) $table->decimal('weight', 10, 2)->nullable()->after('power'); // Weight in KG
            if (!Schema::hasColumn('machines', 'purchase_order_number')) $table->string('purchase_order_number')->nullable()->after('weight');
            if (!Schema::hasColumn('machines', 'received_date')) $table->date('received_date')->nullable()->after('purchase_date');
            if (!Schema::hasColumn('machines', 'manufacturer')) $table->string('manufacturer')->nullable()->after('received_date'); // "Made by"
            if (!Schema::hasColumn('machines', 'supplier')) $table->string('supplier')->nullable()->after('manufacturer');
            if (!Schema::hasColumn('machines', 'price')) $table->decimal('price', 15, 2)->nullable()->after('supplier'); // Including VAT
            if (!Schema::hasColumn('machines', 'manufacturing_year')) $table->year('manufacturing_year')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn([
                'machine_type',
                'description',
                'model',
                'machine_group',
                'engine_type',
                'engine_serial_number',
                'plate_number',
                'power',
                'weight',
                'purchase_order_number',
                'received_date',
                'manufacturer',
                'supplier',
                'price',
                'manufacturing_year',
            ]);
        });
    }
};
