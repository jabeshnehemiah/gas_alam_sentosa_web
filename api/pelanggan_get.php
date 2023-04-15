<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if(isset($_POST['kode'])){
    $sql = "SELECT p.id, p.nama_perusahaan, p.badan_usaha, p.kota, p.marketing_id FROM pelanggans p INNER JOIN users u ON p.marketing_id = u.id WHERE u.kode = '" . $_POST['kode']."'";
  }else{
    $sql = "SELECT p.id, p.kode, p.nama_perusahaan, p.kontak_perusahaan, p.badan_usaha, p.nama_direktur, p.kontak_direktur, p.nama_pelanggan, p.ktp, p.npwp, p.provinsi, p.kota, p.alamat, p.kode_pos, p.status_piutang, u.kode marketing FROM pelanggans p INNER JOIN users u ON p.marketing_id = u.id WHERE p.marketing_id = " . $_SESSION['id'];
  }

  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $res = $stmt->get_result();

  $data = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data[] = $row;
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data];
  echo json_encode($response);
}
