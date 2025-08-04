<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// database/migrations/xxxx_xx_xx_xxxxxx_add_cover_image_to_books_table.php
public function up(): void
{
    Schema::table('books', function (Blueprint $table) {
        $table->string('cover_image')->nullable();
    });
}

public function down(): void
{
    Schema::table('books', function (Blueprint $table) {
        $table->dropColumn('cover_image');
    });
}

};
