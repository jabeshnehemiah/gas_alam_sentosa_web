<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

function generateKode($table, $length, $conn, $kategori_barang = null, $satuan = null, $nama = null)
{
  $month = date('m');
  $year = date('Y');
  switch ($table) {
    case 'users':
      $strings = explode(' ',$nama);
      $kode='';
      foreach($strings as $string){
        $kode.= strtoupper($string[0]);
      }
      $sql = "SELECT COUNT(id) id FROM users WHERE kode LIKE '$kode%'";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $res = $stmt->get_result();

      $id = 0;
      while ($row = $res->fetch_assoc()) {
        // Put data
        $id = intval($row['id']) + 1;
      }
      return $kode . $id;
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
    case 'penawaran_barangs':
      $sql = "SELECT COUNT(id) id FROM penawaran_barangs WHERE MONTH(tanggal_dibuat) = $month AND YEAR(tanggal_dibuat) = $year AND marketing_id = " . $_SESSION['id'];
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $res = $stmt->get_result();

      $id = 0;
      while ($row = $res->fetch_assoc()) {
        // Put data
        $id = intval($row['id']) + 1;
      }
      return "PWR/" . $_SESSION['kode'] . "/$year/" . str_pad($month, 2, "0", STR_PAD_LEFT) . "/" . str_pad(strval($id), $length, "0", STR_PAD_LEFT);
    case 'request_orders':
      $sql = "SELECT COUNT(id) id FROM request_orders WHERE MONTH(tanggal_dibuat) = $month AND YEAR(tanggal_dibuat) = $year AND marketing_id = " . $_SESSION['id'];
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $res = $stmt->get_result();

      $id = 0;
      while ($row = $res->fetch_assoc()) {
        // Put data
        $id = intval($row['id']) + 1;
      }
      return "RO/" . $_SESSION['kode'] . "/$year/" . str_pad($month, 2, "0", STR_PAD_LEFT) . "/" . str_pad(strval($id), $length, "0", STR_PAD_LEFT);
    case 'surat_jalans':
      $sql = "SELECT COUNT(id) id FROM surat_jalans WHERE MONTH(tanggal_dibuat) = $month AND YEAR(tanggal_dibuat) = $year";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $res = $stmt->get_result();

      $id = 0;
      while ($row = $res->fetch_assoc()) {
        // Put data
        $id = intval($row['id']) + 1;
      }
      return "SJ/$year/" . str_pad($month, 2, "0", STR_PAD_LEFT) . "/" . str_pad(strval($id), $length, "0", STR_PAD_LEFT);
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
