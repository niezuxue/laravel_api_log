<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 50)->nullable()->comment('url');
            $table->text('request_header')->nullable()->comment('请求头');
            $table->text('request_body')->nullable()->comment('请求数据');
            $table->unsignedSmallInteger('response_status')->nullable()->comment('response_status');
            $table->text('response_header')->nullable()->comment('返回数据头');
            $table->text('response_body')->nullable()->comment('返回数据');
            $table->string('execute_time', 30)->nullable()->comment('执行时间');
            $table->string('ip', 15)->nullable()->comment('ip');
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
        Schema::dropIfExists('api_logs');
    }
}
