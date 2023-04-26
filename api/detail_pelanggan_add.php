<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $conn->begin_transaction();

    if (isset($_POST['harga_barangs'])) {
      $details = $_POST['harga_barangs'];
      unset($_POST['harga_barangs']);
    }

    // Get keys
    $keys = array_keys($_POST);

    // Get values
    $values = array_values($_POST);

    $sql = "SELECT COUNT(id) count FROM detail_pelanggans WHERE pelanggan_id = " . $_POST['pelanggan_id'];
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $res = $stmt->get_result();

    $count;
    while ($row = $res->fetch_assoc()) {
      $count = $row['count'];
    }

    if (intval($count) >= 5) {
      throw new Exception('Detail pelanggan tidak boleh lebih dari 5');
    }
    // Prepare SQL
    $placeholder = '';
    $params = '';
    $sql = "INSERT INTO detail_pelanggans (";
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
        foreach ($details as $detail) {
          $detail['detail_pelanggan_id'] = $id;
          // Get keys
          $keys = array_keys($detail);

          // Get values
          $values = array_values($detail);

          // Prepare SQL
          $placeholder = '';
          $params = '';
          $sql = "INSERT INTO harga_barangs (";
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
