<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = "UPDATE barangs SET aktif = 0 WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $_POST['id']);
  $stmt->execute();

  $response = ['success' => true, 'message' => 'Berhasil menonaktifkan barang.'];

  echo json_encode($response);
}
