<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the table from the request
  $kodeInit = $_POST['kode'];

  // Get keys
  $keys = array_keys($_POST);

  // Get values
  $values = array_values($_POST);

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "UPDATE request_orders SET ";
  for ($i = 0; $i < count($_POST); $i++) {
    $key = $keys[$i];
    if ($i == count($_POST) - 1) {
      $sql .= "$key = ? ";
    } else {
      $sql .= "$key = ?, ";
    }
    $params .= 's';
  }
  $sql .= "WHERE kode = '$kodeInit'";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param($params, ...$values);
  $stmt->execute();

  $error = "";
  if (isset($_FILES['file_po'])) {
    if (!$_FILES['file_po']['size'] == 0) {
      $target_dir = "../files/barang/";
      $fileType = strtolower(pathinfo($_FILES['file_po']['name'], PATHINFO_EXTENSION));
      $target_file = $_POST['kode'] . '.' . $fileType;
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
          $sql = "UPDATE request_orders SET file_po = '$target_file' WHERE kode = " . $kodeInit;
          $stmt = $conn->prepare($sql);
          $stmt->execute();
        }
      }
    }
  }


  if ($stmt->affected_rows > 0) {
    $response = ['success' => true, 'message' => "Berhasil mengubah data $kodeInit."];
  } else {
    $response = ['success' => false, 'message' => "Gagal mengubah data $kodeInit."];
  }

  echo json_encode($response);
}
