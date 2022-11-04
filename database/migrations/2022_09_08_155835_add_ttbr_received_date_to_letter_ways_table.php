<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTtbrReceivedDateToLetterWaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('letter_ways', function (Blueprint $table) {
            $table->date('ttbr_received_date')->nullable()->after('ttbr_attachment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('letter_ways', function (Blueprint $table) {
            //
        });
    }
}
