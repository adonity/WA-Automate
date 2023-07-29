<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::insert([
            [
                'name' => 'BROADCAST_DELAY',
                'value' => '1000-5000',
            ],
            [
                'name' => 'IP_WHITELIST',
                'value' => '0',
            ],
        ]);
    }
}
