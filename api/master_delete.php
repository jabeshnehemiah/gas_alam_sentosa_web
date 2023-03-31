<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the table from the request
  $table = $_POST['table'];
  $id = $_POST['id'];

  // Prepare SQL
  $sql = "DELETE FROM $table WHERE id = '$id'";

  $stmt = $conn->prepare($sql);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $response = ['success' => true, 'message' => "Berhasil menghapus data $id."];
  } else {
    $response = ['success' => false, 'message' => "Gagal menghapus data $id."];
  }

  echo json_encode($response);
}
