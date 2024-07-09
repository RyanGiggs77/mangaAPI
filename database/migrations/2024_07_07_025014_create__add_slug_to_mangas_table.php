<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToMangasTable extends Migration
{
    public function up()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->string('slug')->unique()->after('title');
        });
    }

    public function down()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
