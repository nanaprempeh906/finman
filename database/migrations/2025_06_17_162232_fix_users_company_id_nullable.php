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
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing foreign key and unique constraint
            $table->dropForeign(['company_id']);
            $table->dropUnique(['email', 'company_id']);

            // Make company_id nullable to allow user registration before company setup
            $table->unsignedBigInteger('company_id')->nullable()->change();

            // Re-add foreign key constraint but allow NULL
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->unsignedBigInteger('company_id')->nullable(false)->change();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['email', 'company_id']);
        });
    }
};
