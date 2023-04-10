<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the table from the request
  $kodeInit = $_POST['kode'];

  // Get keys
  $keys = array_keys($_POST);

  // Get values
  $values = array_values($_POST);

  $success = false;

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "UPDATE surat_jalans SET ";
  for ($i = 0; $i < count($_POST); $i++) {
    $key = $keys[$i];
    if ($i == count($_POST) - 1) {
      $sql .= "$key = ? ";
    } else {
      $sql .= "$key = ?, ";
    }
    $params .= 's';
  }
  $sql .= "WHERE kode = '$kodeInit'";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param($params, ...$values);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $response = ['success' => true, 'message' => "Berhasil mengubah data $kodeInit."];
  } else {
    $response = ['success' => false, 'message' => "Gagal mengubah data $kodeInit."];
  }

  echo json_encode($response);
}
