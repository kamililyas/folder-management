<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_files', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('name');
            $table->double('size')->comment = "Size is stored in kilobytes";
            $table->unsignedInteger('file_type_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('parent_id')->nullable();

            $table->softDeletes();

            $table->foreign('file_types_id')->references('id')->on('file_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('parent_id')->references('id')->on('user_files')->onDelete('set null');

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
        Schema::dropIfExists('user_files');
    }
}
