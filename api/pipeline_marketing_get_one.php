<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the id from the request
  $id = $_POST['id'];

  // Get table data with prepared statement
  $sql = "SELECT pm.id, pm.detail_pelanggan_id, dpm.barang_id, dpm.kuantitas FROM pipeline_marketings pm LEFT JOIN detail_pipeline_marketings dpm ON pm.id = dpm.pipeline_marketing_id WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s',$id);
  $stmt->execute();
  $res = $stmt->get_result();

  $data;
  $barangs = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data = $row;
    if ($row['barang_id'] != null) {
      $barangs[] = ['barang_id' => $row['barang_id'], 'kuantitas' => $row['kuantitas'], 'harga_jual' => $row['harga_jual'], 'ppn' => $row['ppn']];
    }
  }

  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'barangs'=>$barangs];
  echo json_encode($response);
}
