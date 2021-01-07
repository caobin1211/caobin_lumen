<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account', 20)->nullable(false)->default('')->comment("管理员账号");
            $table->char('password', 64)->nullable(false)->default('')->comment("管理员密码");
            $table->string('name', 20)->nullable(false)->default('')->comment("管理员名称");
            $table->char('phone', 15)->nullable(false)->default('')->comment("管理员手机号");
            $table->string('head_image', 100)->nullable(false)->default('')->comment("图像");
            $table->tinyInteger('status')->nullable(false)->default(1)->comment("状态：1-启用，0-禁用");
            $table->string('last_login_ip', 20)->nullable(false)->default('')->comment("最后登录ip");
            $table->timestamp('last_login_at')->nullable()->comment('最后登录时间');
            $table->unsignedInteger('login_times')->default(0)->comment("登录次数");
            $table->timestamps();
            $table->softDeletes();
            $table->index("account", "account_index");
            $table->index("name", "name_index");
            $table->index("phone", "phone_index");
        });
        $prefix = env('DB_PREFIX');
        \Illuminate\Support\Facades\DB::statement("alter table ".$prefix."users comment '管理员用户表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
