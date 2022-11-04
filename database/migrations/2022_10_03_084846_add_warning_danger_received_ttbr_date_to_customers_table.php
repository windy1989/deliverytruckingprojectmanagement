<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarningDangerReceivedTtbrDateToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('warning_date_vendor')->nullable()->after('pic');
            $table->string('danger_date_vendor')->nullable()->after('warning_date_vendor');
            $table->string('warning_date_ttbr')->nullable()->after('danger_date_vendor');
            $table->string('danger_date_ttbr')->nullable()->after('warning_date_ttbr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
