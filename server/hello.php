<?php
// hello.php — your first server-side script.
//
// Everything inside the PHP tags runs ON THE SERVER. The browser never
// sees this code, only the HTML it produces. Compare this file with what
// "View page source" shows in your browser.

// The server's clock, in UTC (Coordinated Universal Time)
$utc = new DateTime('now', new DateTimeZone('UTC'));

// The same moment, in UK local time
$uk = new DateTime('now', new DateTimeZone('Europe/London'));

// The public IP address your request arrived from
$your_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hello from PHP</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 40rem; margin: 3rem auto; padding: 0 1rem; line-height: 1.5; }
    td { padding: 0.3rem 1rem 0.3rem 0; }
    td:first-child { color: #555; }
  </style>
</head>
<body>
  <h1>Hello from PHP</h1>
  <p>This page was built by the server a moment ago. Refresh it and the times change.</p>

  <table>
    <tr><td>Server time (UTC)</td>     <td><?= $utc->format('Y-m-d H:i:s') ?></td></tr>
    <tr><td>UK local time</td>         <td><?= $uk->format('Y-m-d H:i:s T') ?></td></tr>
    <tr><td>PHP version</td>           <td><?= phpversion() ?></td></tr>
    <tr><td>Your public IP address</td><td><?= htmlspecialchars($your_ip) ?></td></tr>
  </table>
</body>
</html>
