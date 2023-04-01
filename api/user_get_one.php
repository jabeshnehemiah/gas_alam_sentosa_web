<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Set fillable columns
  $fillables = ['kode', 'nama', 'username'];

  // Get the id from the request
  $kode = $_POST['kode'];

  // Get table data with prepared statement
  $sql = "SELECT u.kode, u.nama, u.username, r.nama role, d.nama divisi, a.nama atasan FROM users u LEFT JOIN roles r ON u.role_id = r.id LEFT JOIN divisis d ON u.divisi_id = d.id LEFT JOIN users a ON u.atasan_id = a.id WHERE u.aktif = 1 AND u.kode=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $kode);
  $stmt->execute();
  $res = $stmt->get_result();

  $data;
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data = $row;
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'fillables' => $fillables];
  echo json_encode($response);
}
