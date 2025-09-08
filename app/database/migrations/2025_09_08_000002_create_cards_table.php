<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('column_id');
            $table->timestamps();
            $table->foreign('column_id')->references('id')->on('columns')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
