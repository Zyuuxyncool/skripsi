<template>
    <div class="bg-palette min-h-screen">
        <!-- Navbar -->
        <nav class="bg-white shadow">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <h1 class="flex items-center gap-2 text-xl font-bold text-primary">
                    <ion-icon name="analytics-outline" class="text-2xl"></ion-icon>
                    Prediksi Hunian Hotel
                </h1>
                <span class="text-sm text-gray-500">Metode ARIMA – Hotel Salsabila Sweet Home</span>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl space-y-10 px-6 py-6">
            <!-- Grafik & ARIMA -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Grafik -->
                <div class="rounded-2xl border border-blue-100 bg-white p-6 shadow-md">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-primary">
                        <ion-icon name="stats-chart-outline"></ion-icon>
                        Grafik Prediksi vs Aktual
                    </h2>
                    <canvas id="chart" class="h-64 w-full"></canvas>
                </div>

                <!-- ARIMA Step -->
                <div class="rounded-2xl border border-purple-100 bg-white p-6 shadow-md">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-accent">
                        <ion-icon name="search-outline"></ion-icon>
                        Tahapan Penelitian ARIMA
                    </h2>
                    <ol class="list-decimal space-y-2 pl-6 text-sm text-gray-700">
                        <li>Studi Literatur</li>
                        <li>Pengumpulan Data Historis</li>
                        <li>Analisis Deskriptif</li>
                        <li>Uji Stasioneritas</li>
                        <li>Penentuan Orde ARIMA</li>
                        <li>Estimasi Parameter</li>
                        <li>Uji Diagnostik Model</li>
                        <li>Peramalan Masa Depan</li>
                        <li>Evaluasi Akurasi (MAPE)</li>
                    </ol>
                </div>
            </div>

            <!-- Tabel dan Aksi -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-lg">
                <!-- Info & Action -->
                <div class="mb-6 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                    <div class="text-sm text-gray-700">
                        Akurasi <span class="font-semibold">MAPE:</span>
                        <span class="font-bold text-blue-600">{{ mape }}%</span>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button
                            @click="downloadCSV"
                            class="btn-primary flex items-center gap-2 rounded-xl px-4 py-2 text-sm text-white shadow-md hover:opacity-90"
                        >
                            <ion-icon name="download-outline"></ion-icon>
                            Download CSV
                        </button>

                        <form
                            :action="route('forecast.import')"
                            method="POST"
                            enctype="multipart/form-data"
                            class="flex flex-col items-start gap-3 sm:flex-row sm:items-center"
                        >
                            <input type="hidden" name="_token" :value="csrfToken" />

                            <!-- File Picker -->
                            <label
                                class="relative flex cursor-pointer items-center gap-3 rounded-lg border border-dashed border-gray-300 bg-white px-4 py-2 text-sm shadow-sm hover:border-blue-400"
                            >
                                <ion-icon name="document-attach-outline" class="text-xl text-blue-500"></ion-icon>
                                <span class="text-gray-700">
                                    {{ selectedFileName || 'Pilih File Excel (.xlsx, .xls)' }}
                                </span>
                                <input
                                    type="file"
                                    name="file"
                                    accept=".xlsx,.xls"
                                    @change="handleFile"
                                    required
                                    class="absolute top-0 left-0 h-full w-full cursor-pointer opacity-0"
                                />
                            </label>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="btn-success flex items-center gap-2 rounded-xl px-4 py-2 text-sm text-white shadow-md hover:opacity-90"
                            >
                                <ion-icon name="cloud-upload-outline"></ion-icon>
                                Import Excel
                            </button>
                        </form>

                        <a
                            :href="route('forecast.export')"
                            class="btn-accent flex items-center gap-2 rounded-xl px-4 py-2 text-sm text-white shadow-md hover:opacity-90"
                        >
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                            Export Excel
                        </a>
                    </div>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 overflow-hidden rounded-xl text-sm">
                        <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                            <tr>
                                <th class="px-4 py-3 text-left">Periode</th>
                                <th class="px-4 py-3 text-left">Data Aktual</th>
                                <th class="px-4 py-3 text-left">Data Prediksi</th>
                                <th class="px-4 py-3 text-left">MAPE (%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(val, idx) in fullTable" :key="idx" class="transition duration-150 hover:bg-blue-50">
                                <td class="px-4 py-3 font-medium text-gray-800">Periode {{ idx + 1 }}</td>
                                <td class="px-4 py-3 text-gray-800">{{ val.aktual ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-800">{{ val.prediksi ?? '-' }}</td>
                                <td class="px-4 py-3 font-semibold text-blue-600">{{ val.mape ?? '-' }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';
import { saveAs } from 'file-saver';
import { computed, onMounted } from 'vue';
import { ref } from 'vue';

const selectedFileName = ref('');

const handleFile = (e) => {
    const file = e.target.files[0];
    selectedFileName.value = file ? file.name : '';
};

const { props } = usePage();
const csrfToken = props.csrf_token;
const { predictions, actual, mape } = props;

const fullTable = computed(() => {
    const maxLen = Math.max(actual.length, predictions.length);
    return Array.from({ length: maxLen }, (_, i) => {
        const a = actual[i];
        const p = predictions[i];
        let localMape = '-';
        if (a && p && a !== 0) localMape = Math.abs(((a - p) / a) * 100).toFixed(2);
        return { aktual: a ?? null, prediksi: p ?? null, mape: localMape };
    });
});

const downloadCSV = () => {
    const rows = [['Periode', 'Aktual', 'Prediksi', 'MAPE']];
    fullTable.value.forEach((v, i) => {
        rows.push([`Periode ${i + 1}`, v.aktual ?? '-', v.prediksi ?? '-', v.mape + '%']);
    });
    const csvContent = rows.map((e) => e.join(',')).join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    saveAs(blob, 'prediksi_hunian.csv');
};

onMounted(() => {
    if (!actual.length || !predictions.length) return;

    const ctx = document.getElementById('chart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: fullTable.value.map((_, i) => `Periode ${i + 1}`),
            datasets: [
                {
                    label: 'Aktual',
                    data: fullTable.value.map((v) => v.aktual),
                    borderColor: '#1D4ED8',
                    fill: false,
                },
                {
                    label: 'Prediksi',
                    data: fullTable.value.map((v) => v.prediksi),
                    borderColor: '#10B981',
                    borderDash: [5, 5],
                    fill: false,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#374151',
                        font: { size: 14 },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#4B5563',
                    },
                },
                x: {
                    ticks: {
                        color: '#4B5563',
                    },
                },
            },
        },
    });
});
</script>

<style scoped>
body {
    font-family: 'Inter', sans-serif;
}
canvas {
    background: #fff;
    border-radius: 0.75rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}
.bg-palette {
    background-color: #f0f4f8;
}
.text-primary {
    color: #1d4ed8;
}
.text-accent {
    color: #6366f1;
}
.btn-primary {
    background: linear-gradient(to right, #3b82f6, #1d4ed8);
}
.btn-success {
    background: linear-gradient(to right, #10b981, #059669);
}
.btn-accent {
    background: linear-gradient(to right, #8b5cf6, #6366f1);
}
</style>
