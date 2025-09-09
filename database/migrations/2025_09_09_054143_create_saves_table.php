// database/migrations/2023_01_03_000000_create_saves_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('photo_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'photo_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('saves');
    }
};
