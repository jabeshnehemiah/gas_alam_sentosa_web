<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['kode'])) {
    $sql =
      "SELECT ro.id, ro.detail_pelanggan_id, ro.diskon, ro.biaya_tambahan, ro.tanggal_kirim, ro.no_po, ro.tanggal_po, dro.barang_id, dro.kuantitas, dro.harga_jual, dro.ppn, b.harga_beli, ro.kode, CONCAT(p.badan_usaha,' ',p.nama_perusahaan) pelanggan, dp.alamat, ro.tanggal_dibuat, b.nama barang, s.nama satuan, dro.subtotal
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
    $sql =
      "SELECT ro.id, ro.detail_pelanggan_id, ro.diskon, ro.biaya_tambahan, ro.tanggal_kirim, dro.barang_id, dro.kuantitas, dro.harga_jual, dro.ppn, b.harga_beli 
      FROM request_orders ro 
      LEFT JOIN detail_request_orders dro ON ro.id = dro.request_order_id
      LEFT JOIN barangs b ON dro.barang_id = b.id  
      WHERE ro.id = ?";

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
