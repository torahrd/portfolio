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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('shop_id')->constrained();
            $table->integer('review');
            $table->datetime('visit_time')->nullable();
            $table->integer('budget')->nullable();
            $table->string('repeat_munu', 200)->nullable();
            $table->string('intarest_menu', 200)->nullable();
            $table->string('reference_link', 200)->nullable();
            $table->string('memo', 500)->nullable();
            $table->boolean('visit_status')->nullable();
            $table->boolean('private_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
