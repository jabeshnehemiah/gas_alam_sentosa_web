<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get table data with prepared statement
  $start = $_POST['start'];
  $end = $_POST['end'];
  $sql = "SELECT t.id, t.settings_id, CONCAT(v.name, ' (', v.id ,')') vessel, m.name meter, m.consumer_type, CONCAT(f.name, ' (', f.id, ')') fuel_type, s.measure_type, t.reading, u.name uom, t.read_at reading_date, t.synced_at sync_date, t.img image
  FROM transactions t
  INNER JOIN (
    SELECT settings_id, DATE(read_at) AS read_date, MAX(read_at) AS latest_read_at, MAX(id) AS max_id
    FROM transactions
    WHERE DATE(read_at) BETWEEN ? AND ?
    GROUP BY settings_id, DATE(read_at)
  ) lt
  ON t.settings_id = lt.settings_id AND t.read_at = lt.latest_read_at AND t.id = lt.max_id
  INNER JOIN settings s
  ON t.settings_id = s.id
  INNER JOIN vessels v
  ON s.vessel_id = v.id
  INNER JOIN meters m
  ON s.meter_id = m.id
  INNER JOIN fueltypes f
  ON s.fueltype_id = f.id
  INNER JOIN uoms u
  ON s.uom_id = u.id
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $start, $end);
  $stmt->execute();
  $res = $stmt->get_result();

  $data = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data[] = $row;
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data];

  echo json_encode($response);
}
