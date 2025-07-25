<?php
namespace App\Exports;

use App\Models\HotelOccupancy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HotelOccupancyExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return HotelOccupancy::select('month', 'year', 'occupancy')->get();
    }

    public function headings(): array
    {
        return ['month', 'year', 'occupancy'];
    }
}
