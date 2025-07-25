import sys
import json
import pandas as pd
from statsmodels.tsa.arima.model import ARIMA

# Baca data dari input JSON
input = sys.stdin.read()
payload = json.loads(input)
data = payload['data']
n_periods = payload.get('periods', 12)

# Model ARIMA
model = ARIMA(data, order=(1,1,1))  # Sesuaikan order jika perlu
model_fit = model.fit()
forecast = model_fit.forecast(steps=n_periods)

# Output JSON
print(json.dumps(forecast.tolist()))
