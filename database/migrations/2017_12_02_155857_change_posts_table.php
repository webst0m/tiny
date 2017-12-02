<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            if(!Schema::hasColumn('posts', 'fields')){
                $table->mediumText('fields')->nullable();
            }
            // $table->char('type', 10)->default('post')->comment('类型: post: 文章 page: 单页 channel: 频道封面')->change();
            $table->timestamp('top_expired_at')->nullable()->comment('置顶过期时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            //
        });
    }
}
