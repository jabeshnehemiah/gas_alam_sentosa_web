<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['awal']) && isset($_POST['akhir']) && isset($_POST['pelanggan'])) {
    $sql = "SELECT pm.id, p.kode kode_pelanggan, CONCAT(p.badan_usaha,' ',p.nama_perusahaan,' - ',p.kota) pelanggan, dp.alamat, pm.tanggal_dibuat, pm.tanggal_survey, pm.tanggal_instalasi, pm.status_pelanggan, ro.kode request_order FROM pipeline_marketings pm INNER JOIN detail_pelanggans dp ON pm.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id  LEFT JOIN request_orders ro ON pm.request_order_id = ro.id WHERE p.id = " . $_POST['pelanggan'] . " AND pm.tanggal_dibuat >= '" . $_POST['awal'] . "' AND pm.tanggal_dibuat <= '" . $_POST['akhir'] . "'";
  } else {
    $sql =
      "SELECT pm.id, p.kode kode_pelanggan, CONCAT(p.badan_usaha,' ',p.nama_perusahaan,' - ',p.kota) pelanggan, dp.alamat, pm.tanggal_dibuat, pm.tanggal_survey, pm.tanggal_instalasi, pm.status_pelanggan, ro.kode request_order 
      FROM pipeline_marketings pm 
      INNER JOIN detail_pelanggans dp ON pm.detail_pelanggan_id = dp.id 
      INNER JOIN pelanggans p ON dp.pelanggan_id = p.id  
      LEFT JOIN request_orders ro ON pm.request_order_id = ro.id 
      WHERE pm.id IN (
        SELECT MAX(id) id
        FROM pipeline_marketings
        WHERE MONTH(tanggal_dibuat) = '".date('m')."' AND marketing_id = ".$_SESSION['id']. "
        GROUP BY detail_pelanggan_id
      )";
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
