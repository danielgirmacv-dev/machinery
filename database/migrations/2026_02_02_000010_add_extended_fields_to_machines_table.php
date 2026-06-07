<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            if (!Schema::hasColumn('machines', 'machine_group')) {
                $table->string('machine_group', 120)->nullable()->after('machine_name');
            }

            if (!Schema::hasColumn('machines', 'plate_number')) {
                $table->string('plate_number', 120)->nullable()->after('serial_number');
            }

            if (!Schema::hasColumn('machines', 'model')) {
                $table->string('model', 120)->nullable()->after('plate_number');
            }

            if (!Schema::hasColumn('machines', 'description')) {
                $table->text('description')->nullable()->after('model');
            }
        });
    }

    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn(['machine_group', 'plate_number', 'model', 'description']);
        });
    }
};

