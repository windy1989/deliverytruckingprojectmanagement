<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('photo')->nullable();
            $table->string('name');
            $table->string('photo_identity_card')->nullable();
            $table->string('no_identity_card');
            $table->string('photo_driving_licence')->nullable();
            $table->string('no_driving_licence');
            $table->string('type_driving_licence');
            $table->date('valid_driving_licence');
            $table->text('address')->nullable();
            $table->char('status', 1);
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}
