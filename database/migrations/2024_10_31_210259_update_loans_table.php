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
        Schema::table('loans', function (Blueprint $table) {
            $table->unsignedBigInteger('lender_id');
            $table->unsignedBigInteger('borrower_id');

            $table->foreign('lender_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('borrower_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['lender_id']);
            $table->dropForeign(['borrower_id']);
            $table->dropColumn('lender_id');
            $table->dropColumn('borrower_id');
        });
    }
};
