<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtRecoveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_recoveries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("loan_id");
            $table->decimal("monthly_payment_amount", 20,2)->default(0.00);
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->decimal("last_amount_to_pay", 20,2)->default(0.00);
            $table->decimal("end_date_balance", 20,2)->default(0.00);
            $table->date("last_date_to_pay")->nullable();
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
        Schema::dropIfExists('debt_recoveries');
    }
}
