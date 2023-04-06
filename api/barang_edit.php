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
  $sql = "UPDATE barangs SET ";
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
  if (!$_FILES['file_gambar']['size'] == 0) {
    $target_dir = "../img/barang/";
    $fileType = strtolower(pathinfo($_FILES['file_gambar']['name'], PATHINFO_EXTENSION));
    $target_file = $_POST['kode'] . '.' . $fileType;
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["file_gambar"]["tmp_name"]);
    if ($check !== false) {
      $uploadOk = 1;
    } else {
      $uploadOk = 0;
      $error = "File bukan gambar.";
    }

    // Check file size
    if ($_FILES["file_gambar"]["size"] > 2000000) {
      $error = "File lebih dari 2 MB";
      $uploadOk = 0;
    }

    // Allow certain file formats
    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
      $error = "File harus jpg, png, atau jpeg";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
      // if everything is ok, try to upload file
      if (move_uploaded_file($_FILES["file_gambar"]["tmp_name"], $target_dir . $target_file)) {
        $sql = "UPDATE barangs SET file_gambar = '$target_file' WHERE kode = " . $kodeInit;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
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
