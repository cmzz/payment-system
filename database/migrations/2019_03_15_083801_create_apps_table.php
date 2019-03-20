<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id')->nullable();
            $table->bigInteger('app_key')->nullable()->comment('app key');
            $table->string('app_secret')->nullable()->comment('app secret');
            $table->string('rsa_public')->nullable()->comment('rsa公钥');
            $table->string('title')->nullable()->comment('标题');
            $table->string('description', 512)->nullable()->comment('描述');
            $table->string('callback_url')->nullable()->comment('同步回调地址');
            $table->string('notify_url')->nullable()->comment('通知地址');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
