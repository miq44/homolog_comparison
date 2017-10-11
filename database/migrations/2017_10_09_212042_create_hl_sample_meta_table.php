<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHlSampleMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hl_sample_meta_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sample_number');
            $table->integer('number_of_control_replicate');
            $table->integer('number_of_condition_replicate');
            $table->integer('data_table_id');
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
        Schema::dropIfExists('hl_sample_meta_info');
    }
}
