<p align="center">
  <img src="https://user-images.githubusercontent.com/your-username/hotel-banner.png" alt="Hotel Forecast Dashboard" width="100%">
</p>

<h1 align="center">🏨 Hotel Occupancy Forecasting Dashboard</h1>

<p align="center">
  <strong>Accurate hotel room occupancy forecasting using ARIMA + Laravel + Python</strong>
</p>

<p align="center">
  <a href="#-features">Features</a> •
  <a href="#-tech-stack">Tech Stack</a> •
  <a href="#️-installation-guide">Installation</a> •
  <a href="#-usage-flow">Usage Flow</a> •
  <a href="#-folder-structure">Folder Structure</a> •
  <a href="#-license">License</a>
</p>

---

<p align="center">
  <img src="https://img.shields.io/badge/Framework-Laravel-red" />
  <img src="https://img.shields.io/badge/Frontend-Bootstrap%205-blue" />
  <img src="https://img.shields.io/badge/Script-Python-yellow" />
  <img src="https://img.shields.io/badge/Model-ARIMA-purple" />
  <img src="https://img.shields.io/badge/Evaluation-MAPE-orange" />
  <img src="https://img.shields.io/badge/License-MIT-green" />
</p>

---

## 🌟 Features

✅ Interactive dashboard with hotel occupancy chart
✅ Predictive analytics using ARIMA model
✅ MAPE evaluation for model accuracy
✅ Excel import/export support
✅ Clean and responsive UI using Bootstrap 5

---

## 🧱 Tech Stack

| Layer         | Tools                     |
| ------------- | ------------------------- |
| Backend       | Laravel (PHP 8+)          |
| Frontend      | Blade + Bootstrap 5       |
| Forecasting   | Python 3.11 + Statsmodels |
| File Handling | Laravel Excel             |
| Database      | MySQL / MariaDB           |

---

## ⚙️ Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/hotel-forecasting.git
cd hotel-forecasting
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JS Dependencies (Optional, for frontend asset build)

```bash
npm install && npm run dev
```

### 4. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

* `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
* Add to `.env`:

```env
PYTHON_PATH="C:\\Users\\yourname\\AppData\\Local\\Programs\\Python\\Python311\\python.exe"
```

Update `config/services.php`:

```php
'python' => [
  'path' => env('PYTHON_PATH', '/usr/bin/python3'),
],
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Serve Laravel

```bash
php artisan serve
```

### 7. Python Script Setup

```bash
pip install pandas statsmodels
```

---

## 🔁 Usage Flow

1. User uploads historical hotel occupancy via Excel
2. System sends data to `forecast_arima.py`
3. Python processes ARIMA and returns prediction
4. Laravel receives and displays prediction + MAPE chart

---

## 📁 Folder Structure

```
├── app/Services/ForecastService.php
├── forecast_arima.py
├── config/services.php
├── resources/views/ (Bootstrap-based UI)
├── public/ (assets, logos, etc)
├── database/migrations
├── .env.example
└── README.md
```

---


