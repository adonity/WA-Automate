<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tags;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tags::insert([
            [
                'id' => 1,
                'label' => 'Teks',
                'type' => 'text',
                'staktif' => true,
            ],[
                'id' => 2,
                'label' => 'Gambar',
                'type' => 'image',
                'staktif' => true,
            ],[
                'id' => 3,
                'label' => 'Video',
                'type' => 'video',
                'staktif' => true,
            ],[
                'id' => 4,
                'label' => 'Buttons',
                'type' => 'button',
                'staktif' => true,
            ]],[
                'id' => 5,
                'label' => 'PDF',
                'type' => 'pdf',
                'staktif' => true,
            ]
        );
    }
}
