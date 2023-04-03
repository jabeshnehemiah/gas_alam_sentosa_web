<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = "SELECT b.kode, b.nama, b.tipe, b.harga_beli, b.file_gambar, b.kode_acc, k.nama kategori, s.nama satuan FROM barangs b INNER JOIN kategori_barangs k ON b.kategori_barang_id = k.id INNER JOIN satuans s ON b.satuan_id = s.id";
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
