<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['id'])) {
    $sql = "SELECT pm.id, pm.kode, p.kode pelanggan, dp.alamat, pm.pemakaian, pm.tanggal_dibuat, pm.tanggal_survey, pm.tanggal_instalasi, pm.status_pelanggan FROM pipeline_marketings pm INNER JOIN detail_pelanggans dp ON pm.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id WHERE pm.detail_pelanggan_id = " . $_POST['id'];
  } else {
    $sql = "SELECT pm.id, pm.kode, p.kode pelanggan, dp.alamat, pm.pemakaian, pm.tanggal_dibuat, pm.tanggal_survey, pm.tanggal_instalasi, pm.status_pelanggan, ro.kode 'request order' FROM pipeline_marketings pm INNER JOIN detail_pelanggans dp ON pm.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id LEFT JOIN request_orders ro ON pm.id = ro.pipeline_marketing_id WHERE pm.marketing_id = " . $_SESSION['id'];
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
