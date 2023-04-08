<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_SESSION['id'];
  if ($_SESSION['role'] == 3) {
    $sql = "SELECT ro.id, ro.kode, p.kode pelanggan, dp.id detail_pelanggan, dp.alamat, ro.tanggal_dibuat, b.nama barang, pm.kode pipeline, ro.kuantitas, ro.tanggal_kirim, ro.no_po, ro.tanggal_po, ro.file_po, ro.aktif, u.kode marketing, u1.kode divalidasi_oleh FROM request_orders ro INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id INNER JOIN barangs b ON ro.barang_id = b.id INNER JOIN users u ON ro.marketing_id = u.id LEFT JOIN pipeline_marketings pm ON ro.pipeline_marketing_id = pm.id LEFT JOIN users u1 ON ro.manager_id = u1.id WHERE (u.atasan_id = $id AND ro.aktif=1) OR ro.marketing_id = $id";
  } else {
    $sql = "SELECT ro.id, ro.kode, p.kode pelanggan, dp.id detail_pelanggan, dp.alamat, ro.tanggal_dibuat, b.nama barang, pm.kode pipeline, ro.kuantitas, ro.tanggal_kirim, ro.no_po, ro.tanggal_po, ro.file_po, ro.aktif, u.kode marketing, u1.kode divalidasi_oleh FROM request_orders ro INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id INNER JOIN barangs b ON ro.barang_id = b.id INNER JOIN users u ON ro.marketing_id = u.id LEFT JOIN pipeline_marketings pm ON ro.pipeline_marketing_id = pm.id LEFT JOIN users u1 ON ro.manager_id = u1.id WHERE ro.marketing_id = $id";
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
