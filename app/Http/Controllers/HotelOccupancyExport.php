<?php

namespace App\Exports;

use App\Models\HotelOccupancy;
use Maatwebsite\Excel\Concerns\FromCollection;

class HotelOccupancyExport implements FromCollection
{
    public function collection()
    {
        return HotelOccupancy::select('month', 'year', 'occupancy')->get();
    }
}
