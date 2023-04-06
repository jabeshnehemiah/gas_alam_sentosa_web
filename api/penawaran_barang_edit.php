<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the table from the request
  $id = $_POST['id'];

  // Get data from the request
  $inputs = $_POST['inputs'];
  extract($inputs);

  if (!$ppn_id) {
    $ppn_id = null;
  }

  // Get keys
  $keys = array_keys($inputs);

  // Get values
  $values = array_values($inputs);

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "UPDATE penawaran_barangs SET ";
  for ($i = 0; $i < count($inputs); $i++) {
    $key = $keys[$i];
    if ($i == count($inputs) - 1) {
      $sql .= "$key = ? ";
    } else {
      $sql .= "$key = ?, ";
    }
    $params .= 's';
  }
  $sql .= "WHERE id = '$id'";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param($params, $detail_pelanggan_id, $barang_id, $harga_jual, $diskon, $biaya_tambahan, $ppn_id, $nominal_biaya);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $response = ['success' => true, 'message' => "Berhasil mengubah data."];
  } else {
    $response = ['success' => false, 'message' => "Gagal mengubah data."];
  }

  echo json_encode($response);
}
