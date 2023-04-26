<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['alur'])) {
    $alur = $_POST['alur'];
    $sql = "SELECT b.id, b.nama, b.harga_beli, k.nama kategori, s.nama satuan FROM barangs b INNER JOIN kategori_barangs k ON b.kategori_barang_id = k.id INNER JOIN satuans s ON b.satuan_id = s.id WHERE b.alur = '$alur' OR b.alur = 'All'";
  }else if (isset($_POST['detail_pelanggan_id'])) {
    $detail_pelanggan_id = $_POST['detail_pelanggan_id'];
    $sql = "SELECT b.id, b.nama, b.harga_beli, hb.harga_jual, s.nama satuan FROM barangs b INNER JOIN harga_barangs hb ON b.id = hb.barang_id INNER JOIN satuans s ON b.satuan_id = s.id WHERE (b.alur = 'Jual' OR b.alur = 'All') AND hb.detail_pelanggan_id = $detail_pelanggan_id";
  } else {
    $sql = "SELECT b.id, b.kode, b.nama, b.tipe, b.alur, b.harga_beli, b.file_gambar gambar, b.kode_acc, k.nama kategori, s.nama satuan FROM barangs b INNER JOIN kategori_barangs k ON b.kategori_barang_id = k.id INNER JOIN satuans s ON b.satuan_id = s.id";
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
