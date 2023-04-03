<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = "SELECT kode, nama_perusahaan, kontak_perusahaan, badan_usaha, nama_direktur, kontak_direktur, nama_pelanggan, ktp, npwp, provinsi, kota, alamat, kode_pos, status_piutang FROM pelanggans WHERE marketing_id = " . $_SESSION['id'];
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
