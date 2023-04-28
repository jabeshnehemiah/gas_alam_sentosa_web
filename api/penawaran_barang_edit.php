<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $conn->begin_transaction();
    $id = $_POST['id'];
    unset($_POST['id']);
    if (isset($_POST['detail_penawaran_barangs'])) {
      $details = $_POST['detail_penawaran_barangs'];
      unset($_POST['detail_penawaran_barangs']);
    }

    // Get keys
    $keys = array_keys($_POST);

    // Get values
    $values = array_values($_POST);

    // Prepare SQL
    $placeholder = '';
    $params = '';
    $sql = "UPDATE penawaran_barangs SET ";
    for ($i = 0; $i < count($_POST); $i++) {
      $key = $keys[$i];
      if ($i == count($_POST) - 1) {
        $sql .= "$key = ? ";
      } else {
        $sql .= "$key = ?, ";
      }
      $params .= 's';
    }
    $sql .= "WHERE id = '$id'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($params, ...$values);
    $stmt->execute();

    if (isset($details)) {
      $sql = "SELECT jumlah FROM ppns WHERE aktif = 1";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $res = $stmt->get_result();
      $ppn;
      while ($row = $res->fetch_assoc()) {
        $ppn = intval($row['jumlah']);
      }
      if ($ppn) {
        $sql = "DELETE FROM detail_penawaran_barangs WHERE penawaran_barang_id = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        foreach ($details as $detail) {
          $detail['penawaran_barang_id'] = $id;
          $detail['subtotal'] = ceil(intval($detail['kuantitas']) * intval($detail['harga_jual']) + intval($detail['kuantitas']) * intval($detail['harga_jual']) * $ppn / 100);
          // Get keys
          $keys = array_keys($detail);

          // Get values
          $values = array_values($detail);

          // Prepare SQL
          $placeholder = '';
          $params = '';
          $sql = "INSERT INTO detail_penawaran_barangs (";
          for ($i = 0; $i < count($detail); $i++) {
            $key = $keys[$i];
            if ($i == count($detail) - 1) {
              $sql .= "$key";
              $placeholder .= '?';
            } else {
              $sql .= "$key, ";
              $placeholder .= '?,';
            }
            $params .= 's';
          }
          $sql .= ") VALUES(" . $placeholder . ')';
          $stmt = $conn->prepare($sql);
          $stmt->bind_param($params, ...$values);
          $stmt->execute();
        }
        if ($stmt->affected_rows > 0) {
          $response = ['success' => true, 'message' => "Berhasil mengubah data."];
        } else {
          $response = ['success' => false, 'message' => "Gagal mengubah detail."];
        }
      }
    } else {
      $response = ['success' => true, 'message' => "Berhasil mengubah data."];
    }
    $conn->commit();
  } catch (Exception $e) {
    $conn->rollback();
    $response = ['success' => false, 'message' => $e->getMessage()];
  }
  echo json_encode($response);
}
