<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Set fillable columns
  $fillables = [
    'users' => ['id', 'password'],
    'vessels' => ['id', 'name', 'password'],
    'meters' => ['id', 'name', 'consumer_type'],
    'fueltypes' => ['id', 'name'],
    'uoms' => ['name']
  ];

  // Get the table from the request
  $id = $_POST['id'];
  $table = $_POST['table'];

  // Get table data with prepared statement
  $sql = "SELECT * FROM $table WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $res = $stmt->get_result();

  $data;
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data = $row;
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'fillables' => $fillables[$table]];
  echo json_encode($response);
}
