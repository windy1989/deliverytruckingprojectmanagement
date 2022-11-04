<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterWaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_ways', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('destination_id');
            $table->string('number');
            $table->double('weight')->default(0);
            $table->integer('qty')->default(0);
            $table->date('received_date')->nullable();
            $table->string('send_back_attachment')->nullable();
            $table->string('legalize_attachment')->nullable();
            $table->date('legalize_received_date')->nullable();
            $table->string('legalize_send_back_attachment')->nullable();
            $table->date('legalize_send_back_received_date')->nullable();
            $table->integer('ttbr_qty')->nullable();
            $table->string('ttbr_attachment')->nullable();
            $table->char('status', 1);
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
        Schema::dropIfExists('letter_ways');
    }
}
