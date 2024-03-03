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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reference')->unique();
            $table->float('price');
            $table->integer('minQuantity')->nullable();
            $table->integer('maxQuantity')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('serviceId')->nullable();
            $table->foreign('serviceId')
                ->references('id')
                ->on('services')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
