<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHlDatasetConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hl_dataset_configurations', function (Blueprint $table) {
            $table->string('generated_data_table_name', 254)->primary();
            $table->string('dataset_name', 200);
            $table->integer('number_of_samples');
            $table->integer('experiment_type');
            $table->integer('data_type');
            $table->integer('stress_condition_id');
            $table->integer('organism_id');
            $table->text('additional_info');
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
        Schema::dropIfExists('hl_dataset_configurations');
    }
}
