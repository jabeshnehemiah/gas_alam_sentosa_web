<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the id from the request
  $kode = $_POST['kode'];

  // Get table data with prepared statement
  $sql = "SELECT u.id, u.kode, u.nama, u.username, r.nama role, d.nama divisi, a.nama atasan, u.role_id, u.divisi_id, u.atasan_id FROM users u LEFT JOIN roles r ON u.role_id = r.id LEFT JOIN divisis d ON u.divisi_id = d.id LEFT JOIN users a ON u.atasan_id = a.id WHERE u.kode=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $kode);
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
