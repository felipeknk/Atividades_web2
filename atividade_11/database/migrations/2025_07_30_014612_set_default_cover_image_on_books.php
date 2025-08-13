<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetDefaultCoverImageOnBooks extends Migration
{
    public function up()
    {
        DB::table('books')
            ->whereNull('cover_image')
            ->update(['cover_image' => 'default-cover.jpg']);
    }

    public function down()
    {
        DB::table('books')
            ->where('cover_image', 'default-cover.jpg')
            ->update(['cover_image' => null]);
    }
}
