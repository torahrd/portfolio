<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileLinksTable extends Migration
{
    public function up()
    {
        Schema::create('profile_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // インデックス追加
            $table->index(['token', 'expires_at']);
            $table->index(['user_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('profile_links');
    }
}
