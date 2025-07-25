<?php
namespace App\Imports;

use App\Models\HotelOccupancy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HotelOccupancyImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new HotelOccupancy([
            'month' => $row['month'],
            'year' => $row['year'],
            'occupancy' => $row['occupancy'],
        ]);
    }
}
