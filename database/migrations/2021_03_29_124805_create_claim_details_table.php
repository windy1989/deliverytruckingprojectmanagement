<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('claim_id');
            $table->unsignedBigInteger('letter_way_id');
            $table->double('percentage')->default(0);
            $table->double('rupiah')->default(0);
            $table->double('tolerance')->default(0);
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
        Schema::dropIfExists('claim_details');
    }
}
