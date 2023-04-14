<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
include 'generate_kode.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get data from the request
  $_POST['kode']= generateKode('kategori_barangs',3,$conn);

  // Get keys
  $keys = array_keys($_POST);

  // Get values
  $values = array_values($_POST);

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "INSERT INTO kategori_barangs (";
  for ($i = 0; $i < count($_POST); $i++) {
    $key = $keys[$i];
    if ($i == count($_POST) - 1) {
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
    $response = ['success' => true, 'message' => "Berhasil menambahkan data kategori barang."];
  } else {
    $response = ['success' => false, 'message' => "Gagal menambahkan data kategori barang."];
  }

  echo json_encode($response);
}
