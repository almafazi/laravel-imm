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
        Schema::table('material_stocks', function (Blueprint $table) {
            $table->dropColumn('base_qty');
            $table->dropColumn('input_qty');
            $table->dropColumn('output_qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_stocks', function (Blueprint $table) {
            //
        });
    }
};
