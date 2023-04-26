<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $kode = $_POST['kode'];

  $sql = "SELECT id FROM pelanggans WHERE kode = '$kode'";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $id = $row['id'];
  }

  $sql = "SELECT dp.* FROM detail_pelanggans dp INNER JOIN pelanggans p ON dp.pelanggan_id = p.id WHERE p.id = $id AND p.marketing_id = " . $_SESSION['id'];
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $res = $stmt->get_result();

  $data = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data[] = $row;
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'id' => $id];
  echo json_encode($response);
}
