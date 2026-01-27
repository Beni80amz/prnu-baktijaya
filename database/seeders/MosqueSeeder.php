<?php

namespace Database\Seeders;

use App\Models\Mosque;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MosqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mosques = [
            [
                'name' => 'Masjid Jamie Jamiatul Ulum',
                'type' => 'masjid',
                'address' => 'Jl. Jatiwaringin RT 02/02',
                'village' => 'Baktijaya',
                'takmir_name' => 'H. Muhammad Saeful',
                'capacity' => 500,
                'has_wudu_facility' => true,
                'has_parking' => true,
            ],
            [
                'name' => 'Musholla Al-Ikhlas',
                'type' => 'musholla',
                'address' => 'RW 05, Kel. Baktijaya',
                'village' => 'Baktijaya',
                'takmir_name' => 'Ust. Zainuddin',
                'capacity' => 100,
                'has_wudu_facility' => true,
                'has_parking' => false,
            ],
            [
                'name' => 'Masjid Nurul Huda',
                'type' => 'masjid',
                'address' => 'Jl. Raya Baktijaya No. 12',
                'village' => 'Baktijaya',
                'takmir_name' => 'H. Ahmad Syarief',
                'capacity' => 450,
                'has_wudu_facility' => true,
                'has_parking' => true,
            ],
            [
                'name' => 'Musholla Baiturrahman',
                'type' => 'musholla',
                'address' => 'RT 04/09, Baktijaya',
                'village' => 'Baktijaya',
                'takmir_name' => 'Ust. Abdul Rohim',
                'capacity' => 80,
                'has_wudu_facility' => true,
                'has_parking' => false,
            ],
        ];

        foreach ($mosques as $mosque) {
            Mosque::updateOrCreate(
                ['slug' => Str::slug($mosque['name'])],
                $mosque
            );
        }
    }
}
