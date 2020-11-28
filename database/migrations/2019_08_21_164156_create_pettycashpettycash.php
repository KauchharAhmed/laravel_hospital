<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePettycashpettycash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pettycash', function (Blueprint $table) {
            $table->increments('id',11);
            $table->integer('admin_id')->length(11)->unsigned();
            $table->foreign('admin_id')->references('id')->on('admin')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('course_id');
            $table->tinyInteger('type')->comment('1 = Admin 2 = Incharge 3 = Operator');
            $table->decimal('balance',40,2);
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
        Schema::drop('pettycash');
    }
}
