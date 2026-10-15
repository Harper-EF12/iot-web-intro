#!/usr/bin/env python3
"""
upload_bme280.py — read a BME280 and send each reading to your web API.

Settings live in config.py in the same folder (copy it from config.example.py).
Stop the script with Ctrl+C.
"""

import sys
import time

try:
    import config
except ImportError:
    sys.exit("config.py not found. Copy config.example.py to config.py and fill it in.")

import board
import requests
from adafruit_bme280 import basic as adafruit_bme280

URL = config.SERVER_URL.rstrip("/") + "/api.php"

HEADERS = {
    "X-API-Key": config.API_KEY,
    # The hosting silently drops requests that announce themselves as
    # "python-requests", so we give the script its own name instead.
    "User-Agent": "iot-web-intro/1.0",
}

# Connect to the sensor over I2C
i2c = board.I2C()
sensor = adafruit_bme280.Adafruit_BME280_I2C(i2c, address=config.I2C_ADDRESS)

print(f"Sending readings to {URL} every {config.INTERVAL} s. Ctrl+C to stop.")

try:
    while True:
        # 1. Read the sensor
        reading = {
            "temperature": round(sensor.temperature, 2),
            "humidity":    round(sensor.relative_humidity, 2),
            "pressure":    round(sensor.pressure, 2),
        }

        # 2. Send it. If the network is down, report it and carry on.
        try:
            response = requests.post(URL, json=reading, headers=HEADERS, timeout=10)
            print(time.strftime("%H:%M:%S"), reading, "->", response.status_code, response.text.strip())
        except requests.RequestException as err:
            print(time.strftime("%H:%M:%S"), reading, "-> FAILED:", err)

        # 3. Wait before the next reading
        time.sleep(config.INTERVAL)

except KeyboardInterrupt:
    print("\nStopped.")
