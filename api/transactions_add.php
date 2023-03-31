<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get data from the request
  $settings_id = $_POST['settings_id'];
  $reading = $_POST['reading'];
  $read_at = $_POST['read_at'];
  $img = $_POST['img'];
  $username=$_POST['username'];

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "INSERT INTO transactions (settings_id, reading, read_at) VALUES(?,?,?)";


  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $settings_id, $reading, $read_at);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $id = $stmt->insert_id;

    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    file_put_contents('../img/transactions/' . $id . '.jpg', $data);
    $path =  './img/transactions/' . $id . ".jpg";

    $sql = "UPDATE transactions SET img=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $path, $id);
    $stmt->execute();

    $sql1 = "SELECT s.id, s.vessel_id, s.meter_id, m.name meter_name, m.consumer_type meter_consumer_type, s.uom_id, u.name uom_name, s.fueltype_id, f.name fueltype_name, s.active, s.measure_type, s.created_by, s.created_at FROM settings s INNER JOIN meters m ON s.meter_id = m.id INNER JOIN uoms u on s.uom_id = u.id INNER JOIN fueltypes f on s.fueltype_id = f.id WHERE s.vessel_id = '$username'";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute();
    $res = $stmt1->get_result();

    $data = [];
    while ($row = $res->fetch_assoc()) {
      // Put data into array
      $data[] = $row;
    }
    $response = ['success' => true, 'message' => 'Berhasil menambahkan data transactions', 'data' => $data];
  } else {
    $response = ['success' => false, 'message' => "Gagal menambahkan data transactions"];
  }
  echo json_encode($response);
}
