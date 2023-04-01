<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the table from the request
  $kodeInit = $_POST['kode'];

  // Get data from the request
  $inputs = $_POST['inputs'];

  // Get keys
  $keys = array_keys($inputs);

  // Get values
  $values = array_values($inputs);
  for ($i = 0; $i < count($values); $i++) {
    if ($values[$i] == "") {
      $values[$i] = null;
    }
  }
  $inputValues = array_filter($values);

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "UPDATE users SET ";
  for ($i = 0; $i < count($inputs); $i++) {
    $value = !$values[$i] ? 'null' : '?';
    $key = $keys[$i];
    if ($i == count($inputs) - 1) {
      $sql .= "$key = $value ";
    } else {
      $sql .= "$key = $value, ";
    }
    if ($values[$i]) {
      $params .= 's';
    }
  }
  $sql .= "WHERE kode = '$kodeInit'";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param($params, ...$inputValues);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $response = ['success' => true, 'message' => "Berhasil mengubah data $kodeInit."];
  } else {
    $response = ['success' => false, 'message' => "Gagal mengubah data $kodeInit."];
  }

  echo json_encode($response);
}
