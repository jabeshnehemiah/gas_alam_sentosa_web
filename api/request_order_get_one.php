<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['kode'])) {
    // Print
    $sql =
      "SELECT ro.id, ro.detail_pelanggan_id, ro.diskon, ro.biaya_tambahan, ro.tanggal_kirim, ro.no_po, ro.tanggal_po, dro.barang_id, dro.kuantitas, dro.harga_jual, dro.ppn, b.harga_beli, ro.kode, CONCAT(p.badan_usaha,' ',p.nama_perusahaan) pelanggan, dp.alamat, ro.tanggal_dibuat, b.nama barang, s.nama satuan, dro.subtotal, ro.tanggal_konfirmasi
      FROM request_orders ro 
      INNER JOIN detail_pelanggans dp ON ro.detail_pelanggan_id = dp.id
      INNER JOIN pelanggans p ON dp.pelanggan_id = p.id
      LEFT JOIN detail_request_orders dro ON ro.id = dro.request_order_id 
      LEFT JOIN barangs b ON dro.barang_id = b.id 
      LEFT JOIN satuans s ON b.satuan_id = s.id
      WHERE ro.kode = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_POST['kode']);
    $stmt->execute();
    $res = $stmt->get_result();
  } else if (isset($_POST['id'])) {
    // Edit
    $sql =
      "SELECT ro.id, ro.detail_pelanggan_id, ro.diskon, ro.biaya_tambahan, ro.tanggal_kirim, dro.barang_id, (dro.kuantitas - COALESCE(t.kuantitas, 0)) kuantitas, b.harga_beli, dro.harga_jual, dro.ppn, dro.subtotal, b.nama barang, s.nama satuan 
      FROM request_orders ro 
      INNER JOIN detail_request_orders dro ON ro.id = dro.request_order_id 
      INNER JOIN barangs b ON dro.barang_id = b.id 
      INNER JOIN satuans s ON b.satuan_id = s.id 
      LEFT JOIN (
        SELECT dsj.barang_id, SUM(dsj.kuantitas) kuantitas 
        FROM surat_jalans sj 
        INNER JOIN detail_surat_jalans dsj ON sj.id = dsj.surat_jalan_id 
        WHERE sj.request_order_id = 2 
        GROUP BY dsj.barang_id
      ) t ON dro.barang_id = t.barang_id
      WHERE ro.id = ?;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_POST['id']);
    $stmt->execute();
    $res = $stmt->get_result();
  }

  $data = [];
  $barangs = [];
  while ($row = $res->fetch_assoc()) {
    // Put data into array
    $data = $row;
    if ($row['barang_id'] != null) {
      $barangs[] = ['barang_id' => $row['barang_id'], 'kuantitas' => $row['kuantitas'], 'harga_beli' => $row['harga_beli'], 'harga_jual' => $row['harga_jual'], 'ppn' => $row['ppn'], 'barang' => $row['barang'], 'satuan' => $row['satuan'], 'subtotal' => $row['subtotal']];
    }
  }

  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'barangs' => $barangs];
  echo json_encode($response);
}
