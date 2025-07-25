<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelOccupancy;

class HotelOccupancySeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        // Isi data dummy untuk 2 tahun (2023-2024)
        for ($year = 2023; $year <= 2024; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $data[] = [
                    'year' => $year,
                    'month' => $month,
                    'occupancy' => rand(30, 90) + (mt_rand() / mt_getrandmax()), // random float 30.0 - 90.999...
                ];
            }
        }

        HotelOccupancy::insert($data);
    }
}
