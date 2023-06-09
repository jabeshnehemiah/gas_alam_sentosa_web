<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $conn->begin_transaction();
    $id = $_POST['id'];
    unset($_POST['id']);
    if (isset($_POST['detail_request_orders'])) {
      $details = $_POST['detail_request_orders'];
      unset($_POST['detail_request_orders']);
    }

    // Get keys
    $keys = array_keys($_POST);

    // Get values
    $values = array_values($_POST);

    $sql = "SELECT manager_id FROM request_orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $rev = false;
    while ($row = $res->fetch_assoc()) {
      $rev = $row['manager_id'] == null ? false : true;
    }

    if ($rev) {
      $sql =
        "INSERT INTO history_request_orders (diskon, biaya_tambahan, tanggal_kirim, no_po, tanggal_po, request_order_id, detail_pelanggan_id)
        SELECT diskon, biaya_tambahan, tanggal_kirim, no_po, tanggal_po, id, detail_pelanggan_id
        FROM request_orders
        WHERE id = $id";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $idHistory = $stmt->insert_id;

      $sql =
        "INSERT INTO detail_history_request_orders (barang_id, history_request_order_id, harga_beli, kuantitas, harga_jual, ppn, subtotal)
        SELECT barang_id, '$idHistory', harga_beli, kuantitas, harga_jual, ppn, subtotal
        FROM detail_request_orders
        WHERE request_order_id = $id";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
    }

    if (isset($_POST['manager_id'])) {
      $sql = "UPDATE request_orders SET manager_id = ?, tanggal_konfirmasi = NOW() ";
      $params = 's';
    } else {
      $placeholder = '';
      $params = '';
      $sql = "UPDATE request_orders SET manager_id = null, tanggal_konfirmasi = null, ";
      for ($i = 0; $i < count($_POST); $i++) {
        $key = $keys[$i];
        if ($i == count($_POST) - 1) {
          $sql .= "$key = ? ";
        } else {
          $sql .= "$key = ?, ";
        }
        $params .= 's';
      }
    }
    $sql .= "WHERE id = $id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($params, ...$values);
    $stmt->execute();

    $sql = "SELECT kode FROM request_orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $kode;
    while ($row = $res->fetch_assoc()) {
      $kode = $row['kode'];
    }

    $error = "";
    if (isset($_FILES['file_po'])) {
      if (!$_FILES['file_po']['size'] == 0) {
        $target_dir = "../files/po/";
        $fileType = strtolower(pathinfo($_FILES['file_po']['name'], PATHINFO_EXTENSION));
        $target_file = str_replace('/', '_', $kode) . '.' . $fileType;
        $uploadOk = 1;

        // Check file size
        if ($_FILES["file_po"]["size"] > 2000000) {
          $error = "File lebih dari 2 MB";
          $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 1) {
          // if everything is ok, try to upload file
          if (move_uploaded_file($_FILES["file_po"]["tmp_name"], $target_dir . $target_file)) {
            $sql = "UPDATE request_orders SET file_po = '$target_file' WHERE id = $id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
          }
        }
      }
    }

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
        $sql = "DELETE FROM detail_request_orders WHERE request_order_id = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        foreach ($details as $detail) {
          $detail['request_order_id'] = $id;
          $detail['subtotal'] = ceil(intval($detail['kuantitas']) * intval($detail['harga_jual']) + intval($detail['kuantitas']) * intval($detail['harga_jual']) * $ppn / 100);
          // Get keys
          $keys = array_keys($detail);

          // Get values
          $values = array_values($detail);

          // Prepare SQL
          $placeholder = '';
          $params = '';
          $sql = "INSERT INTO detail_request_orders (";
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
          $response = ['success' => true, 'message' => "Berhasil mengubah data. $error"];
        } else {
          $response = ['success' => false, 'message' => "Gagal mengubah detail. $error"];
        }
      }
    } else {
      $response = ['success' => true, 'message' => "Berhasil mengubah data. $error"];
    }
    $conn->commit();
  } catch (Exception $e) {
    $conn->rollback();
    $response = ['success' => false, 'message' => $e->getMessage()];
  }

  echo json_encode($response);
}
