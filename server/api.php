<?php
// api.php — a small REST-style API for your sensor readings.
//
//   GET  api.php            -> the most recent readings, as JSON (oldest first)
//   GET  api.php?limit=50   -> choose how many (1 to 2000, default 200)
//
//   POST api.php            -> add one reading
//        Header:  X-API-Key: <your key>
//        Body:    {"temperature": 21.5, "humidity": 55.0, "pressure": 1013.25}
//
// The server adds the timestamp (in UTC) itself, so the Pi's clock doesn't matter.

require __DIR__ . '/db.php';

header('Content-Type: application/json');

// Send a JSON reply with an HTTP status code, then stop.
function respond(int $status, array $data): void
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Allowed range for each field. Anything outside these is rejected.
// (The BME280 datasheet limits: -40 to 85 C, 0 to 100 %RH, 300 to 1100 hPa.)
$FIELDS = [
    'temperature' => [-40, 85],
    'humidity'    => [0, 100],
    'pressure'    => [300, 1100],
];

try {
    $method = $_SERVER['REQUEST_METHOD'];

    // ---------------- GET: read data ----------------
    if ($method === 'GET') {
        $limit = (int)($_GET['limit'] ?? 200);
        $limit = max(1, min($limit, 2000));

        $stmt = db()->prepare(
            "SELECT id, recorded_at, temperature, humidity, pressure
             FROM readings
             ORDER BY recorded_at DESC, id DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = array_reverse($stmt->fetchAll());   // oldest first, ready for a chart

        // Tidy each row: numbers as numbers, and mark the time as UTC with a "Z"
        foreach ($rows as &$r) {
            $r['id']          = (int)$r['id'];
            $r['recorded_at'] = str_replace(' ', 'T', $r['recorded_at']) . 'Z';
            foreach (array_keys($FIELDS) as $f) {
                $r[$f] = (float)$r[$f];
            }
        }
        unset($r);

        respond(200, ['count' => count($rows), 'readings' => $rows]);
    }

    // ---------------- POST: add a reading ----------------
    if ($method === 'POST') {

        // 1. Who is this? Check the API key.
        $key = $_SERVER['HTTP_X_API_KEY'] ?? '';
        if (!hash_equals(API_KEY, $key)) {
            respond(401, ['error' => 'Missing or wrong API key']);
        }

        // 2. Is the body valid JSON?
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            respond(400, ['error' => 'Body must be a JSON object']);
        }

        // 3. Is every value present, a number, and plausible?
        foreach ($FIELDS as $f => [$min, $max]) {
            if (!array_key_exists($f, $data)) {
                respond(400, ['error' => "Missing field: $f"]);
            }
            if (!is_int($data[$f]) && !is_float($data[$f])) {
                respond(400, ['error' => "$f must be a number"]);
            }
            if ($data[$f] < $min || $data[$f] > $max) {
                respond(400, ['error' => "$f out of range ($min to $max)"]);
            }
        }

        // 4. Store it. A prepared statement keeps the data separate from the SQL,
        //    which is what prevents SQL injection.
        $stmt = db()->prepare(
            "INSERT INTO readings (recorded_at, temperature, humidity, pressure)
             VALUES (UTC_TIMESTAMP(), :temperature, :humidity, :pressure)"
        );
        $stmt->execute([
            ':temperature' => $data['temperature'],
            ':humidity'    => $data['humidity'],
            ':pressure'    => $data['pressure'],
        ]);

        respond(201, ['status' => 'stored', 'id' => (int)db()->lastInsertId()]);
    }

    // ---------------- Anything else ----------------
    header('Allow: GET, POST');
    respond(405, ['error' => 'Method not allowed']);

} catch (PDOException $e) {
    // Don't send database details to the client; they can help an attacker.
    respond(500, ['error' => 'Database error']);
}
