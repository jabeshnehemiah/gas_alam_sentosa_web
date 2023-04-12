<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = "SELECT pb.id, pb.kode, p.kode kode_pelanggan, CONCAT(p.badan_usaha,' ',p.nama_perusahaan,' - ',p.kota) pelanggan, dp.alamat, sum(dpb.subtotal) total_harga, pb.diskon, pb.biaya_tambahan, (sum(dpb.subtotal) - pb.diskon + pb.biaya_tambahan) total_bayar, pb.tanggal_dibuat, u.kode marketing FROM penawaran_barangs pb LEFT JOIN detail_penawaran_barangs dpb ON pb.id = dpb.penawaran_barang_id INNER JOIN detail_pelanggans dp ON pb.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id INNER JOIN users u ON pb.marketing_id = u.id WHERE pb.marketing_id = " . $_SESSION['id']." GROUP BY pb.id";
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
