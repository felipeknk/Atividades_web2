<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('books')
            ->whereNull('cover_image')
            ->update(['cover_image' => 'default-cover.png']);
    }

    public function down(): void
    {
        DB::table('books')
            ->where('cover_image', 'default-cover.png')
            ->update(['cover_image' => null]);
    }
};
