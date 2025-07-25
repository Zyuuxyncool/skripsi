<?php

namespace App\Http\Controllers;

use App\Services\ForecastService;
use Illuminate\Http\Request;
use App\Imports\HotelOccupancyImport;
use App\Exports\HotelOccupancyExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\HotelOccupancy; // ✅ Tambahkan ini

class ForecastController extends Controller
{
    protected $forecastService;

    public function __construct()
    {
        $this->forecastService = new ForecastService();
    }

    public function index()
    {
        $data = $this->forecastService->forecast();
        $mape = $this->forecastService->calculateMAPE(
            array_slice($data['actual'], -count($data['predictions'])),
            $data['predictions']
        );

        return inertia('Forecast', [
            'actual' => $data['actual'],
            'predictions' => $data['predictions'],
            'mape' => $mape,
            'csrf_token' => csrf_token(),
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        // 🔥 Hapus semua data lama
        HotelOccupancy::truncate();

        // 🚀 Import data dari file Excel
        Excel::import(new HotelOccupancyImport, $request->file('file'));

        return redirect()->route('forecast.index')->with('success', 'Data berhasil diimport dan diperbarui.');
    }

    public function export()
    {
        return Excel::download(new HotelOccupancyExport, 'hotel_occupancy.xlsx');
    }
}
