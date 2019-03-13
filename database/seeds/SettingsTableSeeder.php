<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert(
        	[
            	'key' => 'logo',
            	'value' => null,
        	]
        );

        DB::table('settings')->insert(	
        	[
            	'key' => 'site_title',
            	'value' => 'Stock Management',
        	]
        );	

        DB::table('settings')->insert(	
        	[
            	'key' => 'currency',
            	'value' => 'eur',
        	]
        );	

        DB::table('settings')->insert(	
        	[
            	'key' => 'currency_position',
            	'value' => 'before',
        	]
    	);
    }
}
