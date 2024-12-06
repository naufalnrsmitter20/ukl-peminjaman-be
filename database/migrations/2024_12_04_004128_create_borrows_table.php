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
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained(
                table: "users", indexName: "borrows_user_id"
            );
            $table->foreignId("inventory_id")->constrained(
                table: "inventories", indexName: "borrows_inventory_id"
            );
            $table->date("borrow_date")->default(now());
            $table->date("return_date")->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrows');
    }
};