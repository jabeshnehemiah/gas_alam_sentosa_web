<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the id from the request
  $kode = $_POST['kode'];

  // Get table data with prepared statement
  $sql = "SELECT id, kode, nama_perusahaan, kontak_perusahaan, badan_usaha, nama_direktur, kontak_direktur, nama_pelanggan, kontak_pelanggan, ktp, npwp, provinsi, kota, alamat, kode_pos, status_piutang FROM pelanggans WHERE kode = ?";
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
