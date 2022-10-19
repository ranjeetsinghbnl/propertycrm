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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->uuid('ex_property_id')->nullable()->comment('Property uuid from third party API, used for mapping with our DB');
            $table->string('county', 255);
            $table->string('country', 255);
            $table->string('town', 255);
            $table->text('description');
            $table->string('address', 255);
            $table->string('image_full', 255);
            $table->string('image_thumbnail', 255);
            $table->decimal('latitude', 10, 8);
            //longitude is between -180 to 180, thus require 1 digit more than latitude, which is between -90 to 90 degree:
            $table->decimal('longitude', 11, 8);
            $table->unsignedTinyInteger('num_bedrooms');
            $table->unsignedTinyInteger('num_bathrooms');
            $table->decimal('price', 13, 2);
            $table->unsignedInteger('property_type_id')->index();
            $table->enum('type', ['sale', 'rent'])->nullable();
            $table->enum('source', ['api', 'manual'])->default('manual');

            $table->timestamp('property_created_at')->nullable(); // used for storing timestamp of property from third party API
            $table->timestamp('property_updated_at')->nullable(); // used for storing timestamp of property from third party API

            $table->softDeletes();
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
        Schema::dropIfExists('properties');
    }
};
