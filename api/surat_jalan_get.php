<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $awal = '';
  $akhir = '';
  if (isset($_POST['awal']) && $_POST['akhir']) {
    $awal = $_POST['awal'];
    $akhir = $_POST['akhir'];
  }
  if ($_SESSION['role'] < 3) {
    $sql =
      "SELECT sj.id, sj.kode, p.kode kode_pelanggan, CONCAT(p.badan_usaha,' ',p.nama_perusahaan,' - ',p.kota) pelanggan, dp.alamat, sum(dsj.subtotal) total_harga, sj.diskon, sj.biaya_tambahan, (sum(dsj.subtotal) - sj.diskon + sj.biaya_tambahan) total_bayar, ro.kode request_order, sj.tanggal_dibuat, sj.tanggal_kirim, sj.nama_driver driver, u.kode marketing 
    FROM surat_jalans sj 
    LEFT JOIN detail_surat_jalans dsj ON sj.id = dsj.surat_jalan_id 
    INNER JOIN request_orders ro ON sj.request_order_id = ro.id 
    INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id 
    INNER JOIN pelanggans p ON dp.pelanggan_id = p.id 
    INNER JOIN users u ON sj.marketing_id = u.id
    WHERE sj.tanggal_dibuat >= '$awal' AND sj.tanggal_dibuat <= '$akhir'
    GROUP BY sj.id";
  } else if ($_SESSION['role'] == 3) {
    $sql =
      "SELECT sj.id, sj.kode, p.kode kode_pelanggan, CONCAT(p.badan_usaha,' ',p.nama_perusahaan,' - ',p.kota) pelanggan, dp.alamat, sum(dsj.subtotal) total_harga, sj.diskon, sj.biaya_tambahan, (sum(dsj.subtotal) - sj.diskon + sj.biaya_tambahan) total_bayar, ro.kode request_order, sj.tanggal_dibuat, sj.tanggal_kirim, sj.nama_driver driver, u.kode marketing 
    FROM surat_jalans sj 
    LEFT JOIN detail_surat_jalans dsj ON sj.id = dsj.surat_jalan_id 
    INNER JOIN request_orders ro ON sj.request_order_id = ro.id 
    INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id 
    INNER JOIN pelanggans p ON dp.pelanggan_id = p.id 
    INNER JOIN users u ON sj.marketing_id = u.id 
    WHERE sj.tanggal_dibuat >= '$awal' AND sj.tanggal_dibuat <= '$akhir' AND (sj.marketing_id = " . $_SESSION['id'] . " OR u.atasan_id = " . $_SESSION['id'] . ")
    GROUP BY sj.id";
  } else {
    $sql =
      "SELECT sj.id, sj.kode, p.kode kode_pelanggan, CONCAT(p.badan_usaha,' ',p.nama_perusahaan,' - ',p.kota) pelanggan, dp.alamat, sum(dsj.subtotal) total_harga, sj.diskon, sj.biaya_tambahan, (sum(dsj.subtotal) - sj.diskon + sj.biaya_tambahan) total_bayar, ro.kode request_order, sj.tanggal_dibuat, sj.tanggal_kirim, sj.nama_driver driver, u.kode marketing 
    FROM surat_jalans sj 
    LEFT JOIN detail_surat_jalans dsj ON sj.id = dsj.surat_jalan_id 
    INNER JOIN request_orders ro ON sj.request_order_id = ro.id 
    INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id 
    INNER JOIN pelanggans p ON dp.pelanggan_id = p.id 
    INNER JOIN users u ON sj.marketing_id = u.id 
    WHERE sj.tanggal_dibuat >= '$awal' AND sj.tanggal_dibuat <= '$akhir' AND sj.marketing_id = " . $_SESSION['id'] . " 
    GROUP BY sj.id";
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
