<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxReferenceToRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repayments', function (Blueprint $table) {
            $table->string('reference')->nullable()->after('code');
            $table->double('tax')->default(0)->after('reference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repayments', function (Blueprint $table) {
            //
        });
    }
}
