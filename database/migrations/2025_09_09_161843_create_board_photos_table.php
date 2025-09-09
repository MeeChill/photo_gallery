// database/migrations/xxxx_xx_xx_xxxxxx_create_board_photos_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('board_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->foreignId('photo_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['board_id', 'photo_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('board_photos');
    }
};
