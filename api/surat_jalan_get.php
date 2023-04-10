<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_SESSION['id'];
  $sql = "SELECT sj.id, sj.kode, ro.kode request_order, sj.tanggal_dibuat, sj.kuantitas, sj.tanggal_kirim, sj.nama_driver driver, u.kode marketing, p.kode pelanggan, dp.alamat FROM surat_jalans sj INNER JOIN request_orders ro ON sj.request_order_id = ro.id INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id INNER JOIN users u ON sj.marketing_id = u.id WHERE sj.marketing_id = " . $_SESSION['id'];

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
