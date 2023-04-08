<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
include 'generate_kode.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $_POST['kode'] = generateKode('request_orders', 4, $conn);

  // Get keys
  $keys = array_keys($_POST);

  // Get values
  $values = array_values($_POST);

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "INSERT INTO request_orders (";
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

  $error = "";
  if (!$_FILES['file_po']['size'] == 0) {
    $target_dir = "../files/po/";
    $fileType = strtolower(pathinfo($_FILES['file_po']['name'], PATHINFO_EXTENSION));
    $target_file = str_replace('/', '_', $_POST['kode']) . '.' . $fileType;
    $uploadOk = 1;

    // Check file size
    if ($_FILES["file_po"]["size"] > 2000000) {
      $error = "File lebih dari 2 MB";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
      // if everything is ok, try to upload file
      if (move_uploaded_file($_FILES["file_po"]["tmp_name"], $target_dir . $target_file)) {
        $sql = "UPDATE request_orders SET file_po = '$target_file' WHERE kode = '" . $_POST['kode']."'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
      }
    }
  }

  if ($stmt->affected_rows > 0) {
    $response = ['success' => true, 'message' => "Berhasil menambahkan data request order. " . $error];
  } else {
    $response = ['success' => false, 'message' => "Gagal menambahkan data request order. " . $error];
  }

  echo json_encode($response);
}
