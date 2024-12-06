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
        Schema::create('returnhs', function (Blueprint $table) {
            $table->id();
            $table->date("return_date")->default(now());
            $table->date("actual_return_date")->default(now());
            $table->foreignId("borrow_id")->constrained(
                table: "borrows", indexName: "returns_borrow_id"
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returnhs');
    }
};