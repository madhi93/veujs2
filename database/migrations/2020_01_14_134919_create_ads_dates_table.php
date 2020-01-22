<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_dates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('ads_date');
            $table->time('start_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->unsignedBigInteger('ads_id');
            $table->string('active_status')->default('inactive');
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
        Schema::dropIfExists('ads_dates');
    }
}
