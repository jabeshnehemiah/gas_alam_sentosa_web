<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $passwordLama = $_POST['passwordLama'];
  $passwordBaru = $_POST['passwordBaru'];

  $sql = "SELECT password FROM users WHERE id = ".$_SESSION['id'];
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $res = $stmt->get_result();

  while ($row = $res->fetch_assoc()) {
    $passwordDB = $row['password'];
  }

  if (isset($passwordDB)) {
    if (password_verify($passwordLama, $passwordDB)) {
      $passwordBaru = password_hash($passwordBaru,PASSWORD_DEFAULT);
      $sql = "UPDATE users SET password = ?";
      
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('s', $passwordBaru);
      $stmt->execute();
    
      if ($stmt->affected_rows > 0) {
        $response = ['success' => true, 'message' => "Berhasil mengubah password."];
      } else {
        $response = ['success' => false, 'message' => "Gagal mengubah password."];
      }
    } else {
      // Authentication failed; return an error response.
      $response = ['success' => false, 'message' => 'Password lama salah.'];
    }
  }
  echo json_encode($response);
}
