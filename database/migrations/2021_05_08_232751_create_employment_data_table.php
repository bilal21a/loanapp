<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_profile_id');
            $table->string('employment_status')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('employer')->nullable();
            $table->string('proof_of_employment')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('approval_status')->default(false);
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
        Schema::dropIfExists('employment_data');
    }
}
