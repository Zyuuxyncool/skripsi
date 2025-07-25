<?php

namespace App\Services;

use App\Models\HotelOccupancy;

class ForecastService
{
    public function getData(): array
    {
        return HotelOccupancy::orderBy('year')->orderBy('month')->pluck('occupancy')->toArray();
    }

    public function forecast(int $periods = 12): array
    {
        $data = $this->getData();

        if (count($data) < 5) {
            throw new \Exception("Minimal 5 data diperlukan untuk prediksi yang lebih bermakna.");
        }


        $payload = json_encode([
            'data' => $data,
            'periods' => $periods,
        ]);

        $pythonPath = escapeshellcmd(config('services.python.path'));
        $scriptName = 'forecast_arima.py';


        $process = proc_open(
            "$pythonPath $scriptName",
            [
                0 => ['pipe', 'r'], // STDIN
                1 => ['pipe', 'w'], // STDOUT
                2 => ['pipe', 'w'], // STDERR
            ],
            $pipes,
            base_path()
        );

        if (!is_resource($process)) {
            throw new \Exception("Gagal menjalankan proses Python.");
        }

        fwrite($pipes[0], $payload);
        fclose($pipes[0]);

        $result = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        if (!empty($error)) {
            throw new \Exception("Python Error: " . trim($error));
        }

        $predictions = json_decode($result, true);

        if (!is_array($predictions)) {
            throw new \Exception("Output prediksi tidak valid.");
        }

        return [
            'predictions' => $predictions,
            'actual' => $data
        ];
    }

    public function calculateMAPE(array $actual, array $predicted): float
    {
        $n = count($actual);
        $mape = 0;

        for ($i = 0; $i < $n; $i++) {
            if ($actual[$i] != 0 && isset($predicted[$i])) {
                $mape += abs(($actual[$i] - $predicted[$i]) / $actual[$i]);
            }
        }

        return round(($mape / $n) * 100, 2);
    }
}
