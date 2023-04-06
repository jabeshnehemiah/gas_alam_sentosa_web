<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get data from the request
  $inputs = $_POST['inputs'];

  // Get keys
  $keys = array_keys($inputs);

  // Get values
  $values = array_values($inputs);

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "INSERT INTO pipeline_marketings (";
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
    $response = ['success' => true, 'message' => "Berhasil menambahkan data pipeline marketing."];
  } else {
    $response = ['success' => false, 'message' => "Gagal menambahkan data pipeline marketing"];
  }

  echo json_encode($response);
}
