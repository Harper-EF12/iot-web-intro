<?php
// config.example.php — TEMPLATE ONLY.
//
// 1. Make a copy of this file called config.php   (cp config.example.php config.php)
// 2. Fill in config.php with the values from your credentials slip.
// 3. Upload config.php to your website.
//
// config.php is listed in .gitignore, so git will never track it.
// Never put real passwords in this example file.

// --- Database (from your credentials slip) ---
define('DB_HOST', 'your-database-host.example.net');  // host name only, no :3306 on the end
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');

// --- API key (from your credentials slip) ---
// Shared secret your Pi sends with every upload so the server knows it's you.
define('API_KEY', 'your-api-key');
