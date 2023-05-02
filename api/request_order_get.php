<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_SESSION['id'];
  $join = "LEFT";
  if (isset($_POST['dari'])) {
    if ($_POST['dari'] == 'surat_jalan') {
      $join = "INNER";
    }
  }
  if ($_SESSION['role'] < 3) {
    $sql = "SELECT ro.id, ro.kode, p.kode kode_pelanggan, CONCAT(p.badan_usaha,' ',p.nama_perusahaan,' - ',p.kota) pelanggan, dp.alamat, sum(dro.subtotal) total_harga, ro.diskon, ro.biaya_tambahan, (sum(dro.subtotal) - ro.diskon + ro.biaya_tambahan) total_bayar, ro.tanggal_dibuat, ro.tanggal_kirim, ro.no_po, ro.tanggal_po, ro.file_po, u.kode marketing, ro.aktif, u1.kode konfirmasi FROM request_orders ro LEFT JOIN detail_request_orders dro ON ro.id = dro.request_order_id INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id INNER JOIN users u ON ro.marketing_id = u.id $join JOIN users u1 ON ro.manager_id = u1.id WHERE ro.aktif = 1 OR ro.marketing_id = $id GROUP BY ro.id";
  } else if ($_SESSION['role'] == 3) {
    $sql = "SELECT ro.id, ro.kode, p.kode kode_pelanggan, CONCAT(p.badan_usaha,' ',p.nama_perusahaan,' - ',p.kota) pelanggan, dp.alamat, sum(dro.subtotal) total_harga, ro.diskon, ro.biaya_tambahan, (sum(dro.subtotal) - ro.diskon + ro.biaya_tambahan) total_bayar, ro.tanggal_dibuat, ro.tanggal_kirim, ro.no_po, ro.tanggal_po, ro.file_po, u.kode marketing, ro.aktif, u1.kode konfirmasi FROM request_orders ro LEFT JOIN detail_request_orders dro ON ro.id = dro.request_order_id INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id INNER JOIN users u ON ro.marketing_id = u.id $join JOIN users u1 ON ro.manager_id = u1.id WHERE (u.atasan_id = $id AND ro.aktif = 1) OR ro.marketing_id = $id GROUP BY ro.id";
  } else {
    $sql = "SELECT ro.id, ro.kode, p.kode kode_pelanggan, CONCAT(p.badan_usaha,' ',p.nama_perusahaan,' - ',p.kota) pelanggan, dp.alamat, sum(dro.subtotal) total_harga, ro.diskon, ro.biaya_tambahan, (sum(dro.subtotal) - ro.diskon + ro.biaya_tambahan) total_bayar, ro.tanggal_dibuat, ro.tanggal_kirim, ro.no_po, ro.tanggal_po, ro.file_po, u.kode marketing, ro.aktif, u1.kode konfirmasi FROM request_orders ro LEFT JOIN detail_request_orders dro ON ro.id = dro.request_order_id INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id INNER JOIN pelanggans p ON dp.pelanggan_id = p.id INNER JOIN users u ON ro.marketing_id = u.id $join JOIN users u1 ON ro.manager_id = u1.id WHERE ro.marketing_id = $id GROUP BY ro.id";
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
