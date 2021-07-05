<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{

    public function run()
    {
        $this->insertData();
    }


    public function insertData()
    {

        $languages = \App\Models\Language::all();
        foreach ($languages as $language) {
            \App\Models\Pages::insertData($language);
        }
    }

}
