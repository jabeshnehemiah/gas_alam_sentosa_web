<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the username and password from the request.
  $username = $_POST['username'];
  $passwordInput = $_POST['password'];

  $sql = "SELECT u.id, u.kode, u.nama, u.password, u.role_id role, d.nama divisi FROM users u LEFT JOIN divisis d ON u.divisi_id = d.id WHERE username=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $res = $stmt->get_result();

  while ($row = $res->fetch_assoc()) {
    $id = $row['id'];
    $passwordDB = $row['password'];
    $kode = $row['kode'];
    $nama = $row['nama'];
    $role = intval($row['role']);
    $divisi = $row['divisi'];
  }

  if (isset($id)) {
    if (password_verify($passwordInput, $passwordDB)) {
      // Authentication successful; return a success response.
      $response = ['success' => true, 'message' => 'Login berhasil', 'changePassword' => $passwordInput == 'password' ? true : false];
      session_start();
      // Set session variable
      $_SESSION['id'] = $id;
      $_SESSION['kode'] = $kode;
      $_SESSION['username'] = $username;
      $_SESSION['nama'] = $nama;
      $_SESSION['role'] = $role;
      $_SESSION['divisi'] = $divisi;
    } else {
      // Authentication failed; return an error response.
      $response = ['success' => false, 'message' => 'Password salah'];
    }
  } else {
    // Authentication failed; return an error response.
    $response = ['success' => false, 'message' => 'Username tidak ditemukan'];
  }
  echo json_encode($response);
}
