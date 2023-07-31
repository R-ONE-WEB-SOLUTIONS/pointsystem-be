<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_regs', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('address');
            $table->unsignedBigInteger('client_type_id');
            $table->unsignedBigInteger('business_id');
            $table->boolean('registered')->nullable();
            $table->timestamps();

            $table->foreign('client_type_id')->references('id')->on('client_types');
            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_regs');
    }
};
