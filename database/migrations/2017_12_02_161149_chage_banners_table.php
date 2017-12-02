<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChageBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('is_target_blank')->default(true)->comment('链接是否在新窗口打开');
            // banner 启用时间
            $table->timestamp('enabled_at')->nullable()->comment('banner 启用时间');
            // banner 过期时间
            $table->timestamp('expired_at')->nullable()->comment('banner 过期时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            //
        });
    }
}
