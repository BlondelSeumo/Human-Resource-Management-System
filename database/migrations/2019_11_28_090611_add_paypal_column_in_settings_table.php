<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaypalColumnInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('stripe_status')->default('1')->after('stripe_webhook_secret');
            $table->string('paypal_client_id')->nullable()->after('stripe_status');
            $table->string('paypal_secret')->nullable()->after('paypal_client_id');
            $table->boolean('paypal_status')->default('1')->after('paypal_secret');
        });

        Schema::table('stripe_invoices', function (Blueprint $table) {
            $table->enum('payment_method', ['stripe', 'paypal'])->after('company_id');
            $table->string('plan_id')->nullable();
            $table->string('billing_frequency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('paypal_client_id');
            $table->dropColumn('paypal_secret');
            $table->dropColumn('stripe_status');
            $table->dropColumn('paypal_status');
        });
        Schema::table('stripe_invoices', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('plan_id');
            $table->dropColumn('billing_frequency');
        });
    }
}
