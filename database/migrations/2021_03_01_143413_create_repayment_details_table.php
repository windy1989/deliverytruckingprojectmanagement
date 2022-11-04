<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repayment_id');
            $table->unsignedBigInteger('letter_way_id');
            $table->double('price')->default(0);
            $table->double('total')->default(0);
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
        Schema::dropIfExists('repayment_details');
    }
}
