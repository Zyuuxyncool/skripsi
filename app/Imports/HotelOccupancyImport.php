<?php

namespace App\Imports;

use App\Models\HotelOccupancy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HotelOccupancyImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validasi agar tidak insert null
        if (
            !isset($row['month']) || !isset($row['year']) || !isset($row['occupancy']) ||
            $row['month'] === null || $row['year'] === null || $row['occupancy'] === null
        ) {
            return null;
        }

        return new HotelOccupancy([
            'month' => (int) $row['month'],
            'year' => (int) $row['year'],
            'occupancy' => (float) $row['occupancy'],
        ]);
    }
}
