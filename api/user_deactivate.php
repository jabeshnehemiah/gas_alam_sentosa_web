<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the table from the request
  $kode = $_POST['kode'];

  // Prepare SQL
  $sql = "UPDATE users SET aktif = '0' WHERE kode = '$kode'";

  $stmt = $conn->prepare($sql);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $response = ['success' => true, 'message' => "Berhasil menonaktifkan user."];
  } else {
    $response = ['success' => false, 'message' => "Gagal menonaktifkan user."];
  }

  echo json_encode($response);
}
