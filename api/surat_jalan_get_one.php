<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the id from the request
  $kode = $_POST['kode'];

  // Get table data with prepared statement
  $sql =
    "SELECT sj.id, sj.request_order_id, sj.diskon, sj.biaya_tambahan, sj.nama_driver, sj.tanggal_kirim, dsj.barang_id, dsj.kuantitas, dsj.harga_jual, dsj.ppn, b.harga_beli, CONCAT(p.badan_usaha,' ',p.nama_perusahaan) pelanggan, dp.alamat, ro.no_po, sj.tanggal_dibuat, sj.kode, b.nama barang, s.nama satuan
    FROM surat_jalans sj 
    INNER JOIN request_orders ro ON sj.request_order_id = ro.id
    INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id
    INNER JOIN pelanggans p ON dp.pelanggan_id = p.id
    LEFT JOIN detail_surat_jalans dsj ON sj.id = dsj.surat_jalan_id 
    LEFT JOIN barangs b ON dsj.barang_id = b.id 
    LEFT JOIN satuans s ON b.satuan_id = s.id
    WHERE sj.kode = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $kode);
  $stmt->execute();
  $res = $stmt->get_result();

  $data;
  $barangs = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data = $row;
    if ($row['barang_id'] != null) {
      $barangs[] = ['barang_id' => $row['barang_id'], 'kuantitas' => $row['kuantitas'], 'harga_beli' => $row['harga_beli'], 'harga_jual' => $row['harga_jual'], 'ppn' => $row['ppn'], 'barang' => $row['barang'], 'satuan' => $row['satuan']];
    }
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'barangs' => $barangs];

  echo json_encode($response);
}
