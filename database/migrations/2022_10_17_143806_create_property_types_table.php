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
        Schema::create('property_types', function (Blueprint $table) {
            $table->unsignedBigInteger('ex_property_type_id')->index()->comment('Property type id from third party API, used for mapping with our DB');
            $table->string('title', 255);
            $table->text('description');
            $table->enum('source', ['api', 'manual'])->default('manual');
            $table->timestamp('type_created_at')->nullable(); // used for storing timestamp of property_type from third party API
            $table->timestamp('type_updated_at')->nullable(); // used for storing timestamp of property_type from third party API
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
        Schema::dropIfExists('property_types');
    }
};
