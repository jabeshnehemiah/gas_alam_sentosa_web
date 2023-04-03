<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
include 'generate_kode.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get data from the request
  $inputs = $_POST['inputs'];
  $inputs['kode'] = generateKode('barangs', 4, $conn, $inputs['kategori_barang_id'], $inputs['satuan_id']);

  // Get keys
  $keys = array_keys($inputs);

  // Get values
  $values = array_values($inputs);

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "INSERT INTO barangs (";
  for ($i = 0; $i < count($inputs); $i++) {
    $key = $keys[$i];
    if ($i == count($inputs) - 1) {
      $sql .= "$key";
      $placeholder .= '?';
    } else {
      $sql .= "$key, ";
      $placeholder .= '?,';
    }
    $params .= 's';
  }
  $sql .= ") VALUES(" . $placeholder . ')';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param($params, ...$values);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $response = ['success' => true, 'message' => "Berhasil menambahkan data barang."];
  } else {
    $response = ['success' => false, 'message' => "Gagal menambahkan data barang."];
  }

  echo json_encode($response);
}
