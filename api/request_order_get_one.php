<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the id from the request
  $kode = $_POST['kode'];

  // Get table data with prepared statement
  $sql = "SELECT ro.id, ro.detail_pelanggan_id, ro.diskon, ro.biaya_tambahan, ro.tanggal_kirim, ro.no_po, ro.tanggal_po, dro.barang_id, dro.kuantitas, dro.harga_jual, dro.ppn FROM request_orders ro LEFT JOIN detail_request_orders dro ON ro.id = dro.request_order_id WHERE ro.kode = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $kode);
  $stmt->execute();
  $res = $stmt->get_result();

  $data = [];
  $barangs = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data = $row;
    if ($row['barang_id'] != null) {
      $barangs[] = ['barang_id' => $row['barang_id'], 'kuantitas' => $row['kuantitas'], 'harga_jual' => $row['harga_jual'], 'ppn' => $row['ppn']];
    }
  }

  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'barangs' => $barangs];
  echo json_encode($response);
}
