<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
include 'generate_kode.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get data from the request
  $inputs = $_POST['inputs'];
  $inputs['kode']= generateKode('satuans',2,$conn);

  // Get keys
  $keys = array_keys($inputs);

  // Get values
  $values = array_values($inputs);

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "INSERT INTO satuans (";
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
    $response = ['success' => true, 'message' => "Berhasil menambahkan data satuan."];
  } else {
    $response = ['success' => false, 'message' => "Gagal menambahkan data satuan."];
  }

  echo json_encode($response);
}