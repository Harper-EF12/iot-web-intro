# config.example.py — TEMPLATE ONLY.
#
# 1. Make a copy called config.py      (cp config.example.py config.py)
# 2. Fill in config.py with your own values.
#
# config.py is listed in .gitignore, so git will never track it.

# Your website address. Replace N with your number. No slash on the end.
SERVER_URL = "http://piN.mechatron.co.uk"

# Must be exactly the same as API_KEY in your server's config.php.
API_KEY = "your-api-key"

# Seconds between readings.
INTERVAL = 60

# I2C address of your BME280. Check with:  i2cdetect -y 1
# Adafruit Stemma QT boards are usually 0x77; many other boards are 0x76.
I2C_ADDRESS = 0x77
