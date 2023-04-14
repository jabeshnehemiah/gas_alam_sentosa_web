<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['id'])) {
    $sql = "SELECT pm.id, pm.detail_pelanggan_id, pm.tanggal_survey, pm.tanggal_instalasi, pm.status_pelanggan, dpm.barang_id, dpm.kuantitas FROM pipeline_marketings pm LEFT JOIN detail_pipeline_marketings dpm ON pm.id = dpm.pipeline_marketing_id WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_POST['id']);
    $stmt->execute();
    $res = $stmt->get_result();
  } else if (isset($_POST['detail_pelanggan_id'])) {
    $sql =
      "SELECT pm.id, pm.detail_pelanggan_id, pm.tanggal_survey, pm.tanggal_instalasi, pm.status_pelanggan, pm.request_order_id, dpm.barang_id, dpm.kuantitas 
      FROM pipeline_marketings pm 
      LEFT JOIN detail_pipeline_marketings dpm ON pm.id = dpm.pipeline_marketing_id 
      WHERE pm.id = (
        SELECT MAX(id) id
        FROM pipeline_marketings
        WHERE detail_pelanggan_id = ?
      )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_POST['detail_pelanggan_id']);
    $stmt->execute();
    $res = $stmt->get_result();
  }

  $data = [];
  $barangs = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data = $row;
    if ($row['barang_id'] != null) {
      $barangs[] = ['barang_id' => $row['barang_id'], 'kuantitas' => $row['kuantitas']];
    }
  }

  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'barangs' => $barangs];
  echo json_encode($response);
}
