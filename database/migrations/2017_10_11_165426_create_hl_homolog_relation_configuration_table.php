<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHlHomologRelationConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hl_homolog_relation_configuration', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('first_organism_id');
            $table->integer('second_organism_id');
            $table->string('mapping_table_name');
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
        Schema::dropIfExists('hl_homolog_relation_configuration');
    }
}
