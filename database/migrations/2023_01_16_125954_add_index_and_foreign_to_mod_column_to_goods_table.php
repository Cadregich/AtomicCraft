<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexAndForeignToModColumnToGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->index('mod_id', 'goods_mod_idx');
            $table->foreign('mod_id', 'goods_mod_fk')
                ->on('mods')->references('id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->dropForeign('goods_mod_fk');
            $table->dropIndex('goods_mod_idx');
        });
    }
}
