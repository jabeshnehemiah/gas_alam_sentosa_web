<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Set fillable columns
  $fillables = ['vessel_id', 'meter_id', 'uom_id', 'fueltype_id', 'measure_type', 'active'];
  $vessel = $_POST['vessel'];

  // Get table data with prepared statement
  $sql = "SELECT s.id, s.vessel_id, m.name meter_name, u.name uom_name, f.name fueltype_name, s.measure_type, s.active, s.created_by, s.created_at
  FROM settings s
  INNER JOIN meters m
  ON s.meter_id = m.id
  INNER JOIN uoms u
  ON s.uom_id = u.id
  INNER JOIN fueltypes f
  ON s.fueltype_id = f.id
  WHERE s.vessel_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $vessel);
  $stmt->execute();
  $res = $stmt->get_result();

  $data = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data[] = $row;
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'fillables' => $fillables];

  echo json_encode($response);
}
