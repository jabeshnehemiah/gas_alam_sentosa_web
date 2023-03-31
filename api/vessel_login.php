<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the username and password from the request.
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM vessels WHERE id=? AND password=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $username, $password);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    // Authentication successful; return a success response.
    $sql1 = "SELECT s.id, s.vessel_id, s.meter_id, m.name meter_name, m.consumer_type meter_consumer_type, s.uom_id, u.name uom_name, s.fueltype_id, f.name fueltype_name, s.active, s.measure_type, s.created_by, s.created_at FROM settings s INNER JOIN meters m ON s.meter_id = m.id INNER JOIN uoms u on s.uom_id = u.id INNER JOIN fueltypes f on s.fueltype_id = f.id WHERE s.vessel_id = '$username'";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute();
    $res = $stmt1->get_result();

    $data = [];
    while ($row = $res->fetch_assoc()) {
      // Put data into array
      $data[] = $row;
    }
    $response = ['success' => true, 'message' => 'Login berhasil', 'data' => $data];
  } else {
    // Authentication failed; return an error response.
    $response = ['success' => false, 'message' => 'Username atau password salah'];
  }
  echo json_encode($response);
}
