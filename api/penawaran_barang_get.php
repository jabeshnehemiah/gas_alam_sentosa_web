<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = "SELECT pb.id, pb.kode, p.kode pelanggan, dp.alamat, b.nama barang, b.harga_beli, pb.harga_jual, pb.diskon, pb.biaya_tambahan, pp.jumlah ppn, pb.nominal_biaya, u.kode marketing FROM penawaran_barangs pb INNER JOIN detail_pelanggans dp ON pb.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id INNER JOIN barangs b ON pb.barang_id = b.id LEFT JOIN ppns pp ON pb.ppn_id = pp.id INNER JOIN users u ON pb.marketing_id = u.id WHERE pb.marketing_id = " . $_SESSION['id'];
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
