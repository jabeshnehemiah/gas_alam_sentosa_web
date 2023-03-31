<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the username and password from the request.
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT u.nama, r.nama role, d.nama divisi FROM users u INNER JOIN roles r ON u.role_id = r.id LEFT JOIN divisis d ON u.divisi_id = d.id WHERE username=? AND password=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $username, $password);
  $stmt->execute();
  $res = $stmt->get_result();

  $nama;
  $role;
  $divisi;
  while ($row = $res->fetch_assoc()) {
    $nama = $row['nama'];
    $role = $row['role'];
    $divisi = $row['divisi'];
  }

  if ($nama != null) {
    // Authentication successful; return a success response.
    $response = ['success' => true, 'message' => 'Login berhasil'];

    session_start();
    // Set session variable
    $_SESSION['username'] = $username;
    $_SESSION['nama'] = $nama;
    $_SESSION['role'] = $role;
    $_SESSION['divisi'] = $divisi;
  } else {
    // Authentication failed; return an error response.
    $response = ['success' => false, 'message' => 'Username atau password salah'];
  }
  echo json_encode($response);
}
