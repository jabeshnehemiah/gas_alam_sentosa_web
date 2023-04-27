<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['role']) && isset($_POST['divisi'])) {
    if ($_POST['divisi'] == '') {
      $_POST['divisi'] = "null";
    }
    $sql = "SELECT u.id, u.nama, r.nama role FROM users u INNER JOIN roles r ON u.role_id = r.id INNER JOIN divisis d ON u.divisi_id = d.id WHERE u.aktif = 1 AND r.id <= " . $_POST['role'] . " AND d.id = " . $_POST['divisi'];
  } else if ($_SESSION['role'] == 1) {
    $sql = "SELECT u.id, u.kode, u.nama, u.username, r.nama role, d.nama divisi, a.nama atasan, u.aktif FROM users u INNER JOIN roles r ON u.role_id = r.id LEFT JOIN divisis d ON u.divisi_id = d.id LEFT JOIN users a ON u.atasan_id = a.id";
  } else if ($_SESSION['role'] == 2) {
    $sql = "SELECT u.id, u.kode, u.nama, u.username, r.nama role, d.nama divisi, a.nama atasan, u.aktif FROM users u INNER JOIN roles r ON u.role_id = r.id INNER JOIN divisis d ON u.divisi_id = d.id LEFT JOIN users a ON u.atasan_id = a.id WHERE u.aktif = 1";
  } else if ($_SESSION['role'] == 3) {
    $sql = "SELECT u.id, u.kode, u.nama, u.username, r.nama role, d.nama divisi, u.aktif FROM users u INNER JOIN roles r ON u.role_id = r.id INNER JOIN divisis d ON u.divisi_id = d.id INNER JOIN users a ON u.atasan_id = a.id WHERE u.atasan_id = " . $_SESSION['id'] . " AND u.aktif = 1";
  }
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $res = $stmt->get_result();

  $data = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data[] = $row;
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data];
  echo json_encode($response);
}
