<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('id',11);
            $table->string('name',250);
            $table->string('email',250);
            $table->string('mobile',20)->unique();
            $table->string('nid_number',250);
            $table->tinyInteger('type');
            $table->string('password',250);
            $table->dateTime('old_loged_time');
            $table->dateTime('new_loged_time');
            $table->string('recover_code',100);
            $table->mediumText('address');
            $table->date('creatd_at');
            $table->date('modified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin');
    }
}
