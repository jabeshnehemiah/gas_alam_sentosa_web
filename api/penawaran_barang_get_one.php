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
    "SELECT pb.id, pb.detail_pelanggan_id, pb.diskon, pb.biaya_tambahan, dpb.barang_id, dpb.kuantitas, dpb.harga_jual, dpb.ppn, b.harga_beli, pb.kode, CONCAT(p.badan_usaha,' ',p.nama_perusahaan) pelanggan, dp.alamat, pb.tanggal_dibuat, b.nama barang, s.nama satuan, dpb.subtotal
    FROM penawaran_barangs pb 
    INNER JOIN detail_pelanggans dp ON pb.detail_pelanggan_id = dp.id
    INNER JOIN pelanggans p ON dp.pelanggan_id = p.id
    LEFT JOIN detail_penawaran_barangs dpb ON pb.id = dpb.penawaran_barang_id 
    LEFT JOIN barangs b ON dpb.barang_id = b.id 
    LEFT JOIN satuans s ON b.satuan_id = s.id
    WHERE pb.kode = ?";
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
      $barangs[] = ['barang_id' => $row['barang_id'], 'kuantitas' => $row['kuantitas'], 'harga_beli' => $row['harga_beli'], 'harga_jual' => $row['harga_jual'], 'ppn' => $row['ppn'], 'barang' => $row['barang'], 'satuan' => $row['satuan'], 'subtotal' => $row['subtotal']];
    }
  }
  $response = ['success' => true, 'message' => 'Berhasil', 'data' => $data, 'barangs' => $barangs];

  echo json_encode($response);
}
