<?php
// db_test.php — checks that config.php is correct and the database is reachable.

require __DIR__ . '/db.php';

$ok = false;
$error = '';
$info = [];
$tables = [];

try {
    $pdo = db();
    $info = $pdo->query("SELECT VERSION()       AS database_version,
                                NOW()           AS database_local_time,
                                UTC_TIMESTAMP() AS database_utc_time,
                                @@time_zone     AS database_time_zone")->fetch();
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $ok = true;
} catch (PDOException $e) {
    // Showing the error helps you debug today. A finished system would log it
    // instead, because error messages can reveal details useful to an attacker.
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Database test</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 44rem; margin: 3rem auto; padding: 0 1rem; line-height: 1.5; }
    .ok  { color: #0a7a2f; } .bad { color: #b00020; }
    td { padding: 0.3rem 1rem 0.3rem 0; } td:first-child { color: #555; }
    pre { background: #f4f4f4; padding: 0.8rem; white-space: pre-wrap; }
  </style>
</head>
<body>
  <h1>Database test</h1>

<?php if ($ok): ?>
  <p class="ok"><strong>Connected to database <?= htmlspecialchars(DB_NAME) ?>.</strong></p>
  <table>
<?php foreach ($info as $k => $v): ?>
    <tr><td><?= htmlspecialchars($k) ?></td><td><?= htmlspecialchars((string)$v) ?></td></tr>
<?php endforeach; ?>
    <tr><td>tables</td><td><?= $tables ? htmlspecialchars(implode(', ', $tables)) : '(none yet)' ?></td></tr>
  </table>
<?php else: ?>
  <p class="bad"><strong>Could not connect.</strong> The error was:</p>
  <pre><?= htmlspecialchars($error) ?></pre>
  <p>Check every value in config.php against your credentials slip, then upload it again.</p>
<?php endif; ?>
</body>
</html>
