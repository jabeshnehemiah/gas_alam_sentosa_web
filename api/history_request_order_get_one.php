<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql =
    "SELECT hro.id, hro.detail_pelanggan_id, hro.diskon, hro.biaya_tambahan, hro.tanggal_kirim, hro.no_po, hro.tanggal_po, dhro.barang_id, dhro.kuantitas, dhro.harga_jual, dhro.ppn, b.harga_beli, ro.kode, CONCAT(p.badan_usaha,' ',p.nama_perusahaan) pelanggan, dp.alamat, ro.tanggal_dibuat, b.nama barang, s.nama satuan, dhro.subtotal, hro.tanggal_konfirmasi
    FROM history_request_orders hro 
    INNER JOIN request_orders ro ON hro.request_order_id = ro.id
    INNER JOIN detail_pelanggans dp ON hro.detail_pelanggan_id = dp.id
    INNER JOIN pelanggans p ON dp.pelanggan_id = p.id
    LEFT JOIN detail_history_request_orders dhro ON hro.id = dhro.history_request_order_id 
    LEFT JOIN barangs b ON dhro.barang_id = b.id 
    LEFT JOIN satuans s ON b.satuan_id = s.id
    WHERE hro.id = (
      SELECT MAX(id)
      FROM history_request_orders
      WHERE request_order_id = ?
    )";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $_POST['id']);
  $stmt->execute();
  $res = $stmt->get_result();

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
