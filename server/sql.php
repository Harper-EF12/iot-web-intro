<?php
// sql.php — a simple SQL playground.
//
// HOW TO USE IT
//   1. Edit the $sql line below in nano.
//   2. Save, upload sql.php again with FileZilla (choose Overwrite).
//   3. Refresh sql.php in your browser to see the result.
//
// One statement at a time. The SQL is written HERE, in the file, on purpose.
// Never change this script to take SQL from the URL or a form: anyone on the
// internet could then run any SQL they liked against your database.

// ======================= EDIT THE SQL BELOW ==========================

$sql = "SHOW TABLES";

// =====================================================================

// Examples to copy into $sql above (the tutorial walks through them in order):
//
// 1) Create the table for your BME280 readings:
//
//    CREATE TABLE readings (
//        id          INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
//        recorded_at DATETIME      NOT NULL,
//        temperature DECIMAL(5,2)  NOT NULL,
//        humidity    DECIMAL(5,2)  NOT NULL,
//        pressure    DECIMAL(6,2)  NOT NULL,
//        INDEX idx_recorded_at (recorded_at)
//    )
//
// 2) Look at the table's design:
//      DESCRIBE readings
//
// 3) Add a test row by hand (UTC_TIMESTAMP() is the current time in UTC):
//      INSERT INTO readings (recorded_at, temperature, humidity, pressure)
//      VALUES (UTC_TIMESTAMP(), 21.5, 55.0, 1013.25)
//
// 4) See what's in the table:
//      SELECT * FROM readings ORDER BY recorded_at DESC LIMIT 20
//
// 5) Ask a question of the data:
//      SELECT COUNT(*) AS n, MIN(temperature), MAX(temperature), AVG(temperature) FROM readings
//
// 6) Remove the test row (check the id first!):
//      DELETE FROM readings WHERE id = 1

require __DIR__ . '/db.php';

$columns = [];
$rows = [];
$message = '';
$error = '';

try {
    $stmt = db()->query($sql);
    if ($stmt->columnCount() > 0) {
        // The statement returned rows (SELECT, SHOW, DESCRIBE ...)
        $rows = $stmt->fetchAll();
        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $columns[] = $stmt->getColumnMeta($i)['name'];
        }
        $message = count($rows) . ' row(s) returned.';
    } else {
        // The statement changed something (CREATE, INSERT, UPDATE, DELETE ...)
        $message = 'Done. ' . $stmt->rowCount() . ' row(s) affected.';
    }
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SQL playground</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 60rem; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
    pre  { background: #f4f4f4; padding: 0.8rem; white-space: pre-wrap; }
    .ok  { color: #0a7a2f; } .bad { color: #b00020; }
    table { border-collapse: collapse; margin-top: 1rem; }
    th, td { border: 1px solid #ccc; padding: 0.3rem 0.6rem; text-align: left; font-family: ui-monospace, monospace; font-size: 0.9rem; }
    th { background: #f0f0f0; }
    .wrap { overflow-x: auto; }
  </style>
</head>
<body>
  <h1>SQL playground</h1>
  <p>The SQL that ran:</p>
  <pre><?= htmlspecialchars(trim($sql)) ?></pre>

<?php if ($error): ?>
  <p class="bad"><strong>Error:</strong></p>
  <pre><?= htmlspecialchars($error) ?></pre>
<?php else: ?>
  <p class="ok"><strong><?= htmlspecialchars($message) ?></strong></p>
<?php endif; ?>

<?php if ($columns): ?>
  <div class="wrap">
  <table>
    <tr><?php foreach ($columns as $c): ?><th><?= htmlspecialchars($c) ?></th><?php endforeach; ?></tr>
<?php foreach ($rows as $r): ?>
    <tr><?php foreach ($columns as $c): ?><td><?= htmlspecialchars((string)($r[$c] ?? 'NULL')) ?></td><?php endforeach; ?></tr>
<?php endforeach; ?>
  </table>
  </div>
<?php endif; ?>
</body>
</html>
