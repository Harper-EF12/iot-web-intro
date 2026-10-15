# IoT web app introduction

A step-by-step introduction to building a web-connected IoT system with a Raspberry Pi, PHP and MySQL:

1. A plain HTML page
2. A PHP page (code that runs on the server)
3. PHP connecting to a MySQL database
4. A SQL playground to create and explore your table
5. A JSON API, tested with `curl`
6. JavaScript charts with Chart.js
7. A Python script sending real BME280 readings from the Pi

## Getting started

On your Raspberry Pi, open a terminal and run:

```bash
cd ~
git clone https://github.com/Harper-EF12/iot-web-intro.git
```

Then open `tutorial.html` in the browser (double-click it in the file manager) and follow it from Step 0.

## What's in here

```
server/                 files that go on your website (via FileZilla)
  index.html            Step 1: Hello world
  hello.php             Step 2: PHP
  config.example.php    Step 3: template; copy to config.php and fill in
  db.php                Step 3: database connection (shared by the other PHP files)
  db_test.php           Step 3: connection check
  sql.php               Step 4: SQL playground
  api.php               Step 5: GET/POST JSON API
  chart.html            Step 6: dashboard
pi/                     files that stay on the Pi
  config.example.py     Step 7: template; copy to config.py and fill in
  upload_bme280.py      Step 7: reads the BME280 and POSTs to api.php
  requirements.txt
tutorial.html           the tutorial
```

## Credentials

`server/config.php` and `pi/config.py` hold your real passwords and API key. Both are in
`.gitignore`, so git never tracks them. Only the `*.example.*` templates live in the repository.
