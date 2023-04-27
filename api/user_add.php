<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
include 'generate_kode.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $_POST['kode'] = generateKode('users', 0, $conn, null, null, $_POST['nama']);
    $_POST['password'] = password_hash('password', PASSWORD_DEFAULT);
    if ($_POST['atasan_id'] == '') {
      unset($_POST['atasan_id']);
    }

    // Get keys
    $keys = array_keys($_POST);

    // Get values
    $values = array_values($_POST);

    // Prepare SQL
    $placeholder = '';
    $params = '';
    $sql = "INSERT INTO users (";
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
      $response = ['success' => true, 'message' => "Berhasil menambahkan data user."];
    } else {
      $response = ['success' => false, 'message' => "Gagal menambahkan data user."];
    }
  } catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
  }

  echo json_encode($response);
}
