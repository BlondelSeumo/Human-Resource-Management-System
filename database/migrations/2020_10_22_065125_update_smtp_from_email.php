<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSmtpFromEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('mail_from_name')->nullable();
            $table->string('mail_from_email')->nullable();
        });

        $setting = Setting::first();
        if (!is_null($setting)) {
            $setting->mail_from_name = $setting->main_name;
            $setting->mail_from_email = $setting->email;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email', function (Blueprint $table) {
            //
        });
    }
}
