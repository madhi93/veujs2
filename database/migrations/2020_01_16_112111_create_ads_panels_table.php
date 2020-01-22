<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsPanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_panels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pannel_id');
            $table->unsignedBigInteger('ads_id')->nullable();
            $table->integer('timer')->default(30);
            $table->integer('panel_row')->nullable();
            $table->integer('order_column')->nullable();
            $table->timestamps();
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_panels');
    }
}
