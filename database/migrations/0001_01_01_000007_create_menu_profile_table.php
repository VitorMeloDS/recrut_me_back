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
        Schema::create('menu_profile', function (Blueprint $table) {
        $table->unsignedBigInteger('id_menu');
        $table->unsignedBigInteger('id_profile');
        $table->timestamps();

        $table->foreign('id_menu')->references('id')->on('menu')->onDelete('cascade');
        $table->foreign('id_profile')->references('id')->on('profile')->onDelete('cascade');

        $table->primary(['id_menu', 'id_profile']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_profile');
    }
};