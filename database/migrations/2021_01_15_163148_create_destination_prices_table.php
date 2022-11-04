<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinationPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destination_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id');
            $table->unsignedBigInteger('unit_id');
            $table->date('date');
            $table->double('price_vendor')->default(0);
            $table->double('price_customer')->default(0);
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
        Schema::dropIfExists('destination_prices');
    }
}
