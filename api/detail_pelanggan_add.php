<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get data from the request
  $inputs = $_POST['inputs'];

  // Get keys
  $keys = array_keys($inputs);

  // Get values
  $values = array_values($inputs);

  $sql = "SELECT COUNT(id) count FROM detail_pelanggans WHERE pelanggan_id = " . $inputs['pelanggan_id'];
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $res = $stmt->get_result();

  $count;
  while ($row = $res->fetch_assoc()) {
    $count = $row['count'];
  }

  if (intval($count) < 5) {
    // Prepare SQL
    $placeholder = '';
    $params = '';
    $sql = "INSERT INTO detail_pelanggans (";
    for ($i = 0; $i < count($inputs); $i++) {
      $key = $keys[$i];
      if ($i == count($inputs) - 1) {
        $sql .= "$key";
        $placeholder .= '?';
      } else {
        $sql .= "$key, ";
        $placeholder .= '?,';
      }
      $params .= 's';
    }
    $sql .= ") VALUES(" . $placeholder . ')';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($params, ...$values);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
      $response = ['success' => true, 'message' => "Berhasil menambahkan data."];
    } else {
      $response = ['success' => false, 'message' => "Gagal menambahkan data."];
    }
  }else{
    $response = ['success' => false, 'message' => "Detail pelanggan tidak boleh lebih dari 5."];
  }

  echo json_encode($response);
}
