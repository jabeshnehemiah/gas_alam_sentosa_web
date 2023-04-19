<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  unset($_POST['id']);
  // Get keys
  $keys = array_keys($_POST);

  // Get values
  $values = array_values($_POST);

  // Prepare SQL
  $placeholder = '';
  $params = '';
  $sql = "UPDATE pelanggans SET ";
  for ($i = 0; $i < count($_POST); $i++) {
    $key = $keys[$i];
    if ($i == count($_POST) - 1) {
      $sql .= "$key = ? ";
    } else {
      $sql .= "$key = ?, ";
    }
    $params .= 's';
  }
  $sql .= "WHERE id = $id";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param($params, ...$values);
  $stmt->execute();

  if ($stmt->affected_rows > 0) {
    $response = ['success' => true, 'message' => "Berhasil mengubah data."];
  } else {
    $response = ['success' => false, 'message' => "Gagal mengubah data."];
  }

  echo json_encode($response);
}
