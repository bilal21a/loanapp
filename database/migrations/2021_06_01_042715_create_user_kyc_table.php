<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserKycTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_kyc', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_profile_id');
            $table->string('type');
            $table->string('value')->nullable();
            $table->decimal('points', 20,2)->default(0);
            $table->boolean('status')->default(false);
            $table->boolean('approval_status')->default(false);
            $table->text('reason_for_approval')->nullable();
            $table->text('reason_for_disapproval')->nullable();
            $table->string('last_location')->nullable();
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
        Schema::dropIfExists('user_kyc');
    }
}
