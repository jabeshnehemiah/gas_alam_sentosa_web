<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the id from the request
  $id = $_POST['id'];

  // Get table data with prepared statement
  $sql =
    "SELECT dp.*, hb.barang_id, hb.harga_jual, b.nama barang, s.nama satuan
    FROM detail_pelanggans dp
    LEFT JOIN harga_barangs hb ON dp.id = hb.detail_pelanggan_id
    LEFT JOIN barangs b ON hb.barang_id = b.id
    LEFT JOIN satuans s ON b.satuan_id = s.id
    WHERE dp.id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $res = $stmt->get_result();

  $data;
  $barangs = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data = $row;
    if ($row['barang_id'] != null) {
      $barangs[] = ['barang_id' => $row['barang_id'], 'harga_jual' => $row['harga_jual'], 'barang' => $row['barang'], 'satuan' => $row['satuan']];
    }
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'barangs' => $barangs];
  echo json_encode($response);
}
