<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the id from the request
  $id = $_POST['id'];

  // Get table data with prepared statement
  $sql = "SELECT * FROM pipeline_marketings WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $res = $stmt->get_result();

  $data;
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data = $row;
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data];
  echo json_encode($response);
}
