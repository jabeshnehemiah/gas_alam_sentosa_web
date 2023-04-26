<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = "UPDATE request_orders SET file_po = null WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $_POST['id']);
  $stmt->execute();

  $response = ['success' => true, 'message' => 'Berhasil menghapus file PO.'];

  echo json_encode($response);
}
