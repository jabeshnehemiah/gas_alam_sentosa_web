<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['id'])) {
    $sql =
      "SELECT pm.id, pm.detail_pelanggan_id, pm.tanggal_survey, pm.tanggal_instalasi, pm.status_pelanggan, dpm.barang_id, dpm.kuantitas 
      FROM pipeline_marketings pm 
      LEFT JOIN detail_pipeline_marketings dpm ON pm.id = dpm.pipeline_marketing_id 
      WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_POST['id']);
    $stmt->execute();
    $res = $stmt->get_result();
  } else if (isset($_POST['detail_pelanggan_id'])) {
    $sql =
      "SELECT pm.id, pm.detail_pelanggan_id, pm.tanggal_survey, pm.tanggal_instalasi, pm.status_pelanggan, pm.request_order_id, dpm.barang_id, dpm.kuantitas, b.harga_beli, hb.harga_jual
      FROM pipeline_marketings pm 
      LEFT JOIN detail_pipeline_marketings dpm ON pm.id = dpm.pipeline_marketing_id 
      LEFT JOIN barangs b ON dpm.barang_id = b.id
      LEFT JOIN harga_barangs hb ON hb.barang_id = dpm.barang_id AND hb.detail_pelanggan_id = pm.detail_pelanggan_id
      WHERE pm.id = (
        SELECT MAX(id) id
        FROM pipeline_marketings
        WHERE detail_pelanggan_id = ?
      );";

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
      if(isset($_POST['detail_pelanggan_id'])){
        $barangs[] = ['barang_id' => $row['barang_id'], 'kuantitas' => $row['kuantitas'], 'harga_beli' => $row['harga_beli'], 'harga_jual' => $row['harga_jual']];
      }else{
        $barangs[] = ['barang_id' => $row['barang_id'], 'kuantitas' => $row['kuantitas']];
      }
    }
  }

  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'barangs' => $barangs];
  echo json_encode($response);
}
