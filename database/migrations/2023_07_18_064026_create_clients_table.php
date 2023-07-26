<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('address');
            $table->boolean('active');
            $table->unsignedBigInteger('client_type_id');
            $table->unsignedBigInteger('business_id');
            $table->timestamps();

        });

        // Set the starting value for the id column and enable auto-increment from 1.
        // Note: The next registered client will have an id of 100000001
        DB::statement('ALTER TABLE clients AUTO_INCREMENT = 100000001');

        // Add the foreign key constraint after setting the starting value to avoid errors.
        Schema::table('clients', function (Blueprint $table) {
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
        Schema::dropIfExists('clients');
    }
};
