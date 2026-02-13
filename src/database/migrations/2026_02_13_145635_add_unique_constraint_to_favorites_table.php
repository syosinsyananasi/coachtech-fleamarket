<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->unique(['user_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'item_id']);
        });
    }
}
