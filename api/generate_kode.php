<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

function generateKode($table, $length, $conn, $kategori_barang = 0, $satuan = 0)
{
  switch ($table) {
    case 'barangs':
      $sql = "SELECT COUNT(b.id) id, kb.kode kategori_barang, s.kode satuan FROM barangs b INNER JOIN kategori_barangs kb ON b.kategori_barang_id = kb.id INNER JOIN satuans s ON b.satuan_id = s.id WHERE kb.id = $kategori_barang AND s.id = $satuan";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $res = $stmt->get_result();

      $id = 0;
      while ($row = $res->fetch_assoc()) {
        // Put data
        $id = intval($row['id']) + 1;
        $kategori_barang = $row['kategori_barang'];
        $satuan = $row['satuan'];
      }
      return $kategori_barang . $satuan . str_pad(strval($id), $length, "0", STR_PAD_LEFT);
    default:
      $sql = "SELECT MAX(id) id FROM $table";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $res = $stmt->get_result();

      $id = 0;
      while ($row = $res->fetch_assoc()) {
        // Put data
        $id = intval($row['id']) + 1;
      }
      return str_pad(strval($id), $length, "0", STR_PAD_LEFT);
  }
}
