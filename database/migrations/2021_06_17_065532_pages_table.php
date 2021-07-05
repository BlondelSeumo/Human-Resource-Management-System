<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("language_id")->nullable();
            $table->foreign("language_id")->references("id")->on("languages")->onUpdate("cascade")->onDelete("SET NULL");
            $table->string('title');
            $table->text('slug');
            $table->longText('description');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\Artisan::call('db:seed', array('--class' => 'PagesTableSeeder'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
