<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
include 'generate_kode.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $conn->begin_transaction();
    // Get data from the request
    $_POST['kode'] = generateKode('surat_jalans', 4, $conn);
    if (isset($_POST['detail_surat_jalans'])) {
      $details = $_POST['detail_surat_jalans'];
      unset($_POST['detail_surat_jalans']);
    }

    // Get keys
    $keys = array_keys($_POST);

    // Get values
    $values = array_values($_POST);

    // Prepare SQL
    $placeholder = '';
    $params = '';
    $sql = "INSERT INTO surat_jalans (";
    for ($i = 0; $i < count($_POST); $i++) {
      $key = $keys[$i];
      if ($i == count($_POST) - 1) {
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

    if ($stmt->affected_rows > 0) {
      $id = $stmt->insert_id;

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
          foreach ($details as $detail) {
            $detail['surat_jalan_id'] = $id;
            $subtotal = intval($detail['kuantitas']) * intval($detail['harga_jual']);
            $detail['subtotal'] = ceil($subtotal + $ppn * $subtotal / 100);
            // Get keys
            $keys = array_keys($detail);

            // Get values
            $values = array_values($detail);

            // Prepare SQL
            $placeholder = '';
            $params = '';
            $sql = "INSERT INTO detail_surat_jalans (";
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
            $response = ['success' => true, 'message' => "Berhasil menambahkan data."];
          } else {
            $response = ['success' => false, 'message' => "Gagal menambahkan detail."];
          }
        }
      } else {
        $response = ['success' => true, 'message' => "Berhasil menambahkan data."];
      }
    } else {
      $response = ['success' => false, 'message' => "Gagal menambahkan data."];
    }
    $conn->commit();
  } catch (Exception $e) {
    $conn->rollback();
    $response = ['success' => false, 'message' => $e->getMessage()];
  }
  echo json_encode($response);
}
