<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
include 'generate_kode.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $conn->begin_transaction();

    $sql = "SELECT tanggal_dibuat FROM request_orders WHERE detail_pelanggan_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_POST['detail_pelanggan_id']);
    $stmt->execute();
    $res = $stmt->get_result();

    $tanggal = "";
    while ($row = $res->fetch_assoc()) {
      // Put data into array
      $tanggal = $row['tanggal_dibuat'];
    }

    if ($tanggal == date('Y-m-d')) {
      throw new Exception('RO untuk tiap detail pelanggan hanya dapat dibuat sehari sekali.');
    }

    // Get data from the request
    $_POST['kode'] = generateKode('request_orders', 4, $conn);
    if (isset($_POST['detail_request_orders'])) {
      $details = $_POST['detail_request_orders'];
      unset($_POST['detail_request_orders']);
    }

    // Get keys
    $keys = array_keys($_POST);

    // Get values
    $values = array_values($_POST);

    // Prepare SQL
    $placeholder = '';
    $params = '';
    $sql = "INSERT INTO request_orders (";
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
      $idOrder = $stmt->insert_id;

      $error = "";
      if (isset($_FILES['file_po'])) {
        if (!$_FILES['file_po']['size'] == 0) {
          $target_dir = "../files/po/";
          $fileType = strtolower(pathinfo($_FILES['file_po']['name'], PATHINFO_EXTENSION));
          $target_file = str_replace('/', '_', $_POST['kode']) . '.' . $fileType;
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
              $sql = "UPDATE request_orders SET file_po = '$target_file' WHERE id = $idOrder";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
            }
          }
        }
      }

      $sql = "SELECT * FROM pipeline_marketings WHERE detail_pelanggan_id=? AND marketing_id=? AND YEAR(tanggal_dibuat)=" . date('Y') . " AND MONTH(tanggal_dibuat)=" . date('m') . " ORDER BY id DESC LIMIT 1";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('ss', $_POST['detail_pelanggan_id'], $_POST['marketing_id']);
      $stmt->execute();
      $res = $stmt->get_result();

      while ($row = $res->fetch_assoc()) {
        // Put data into array
        $data = $row;
      }

      if (isset($data)) {
        $sql = "INSERT INTO pipeline_marketings (detail_pelanggan_id, marketing_id, tanggal_survey, tanggal_instalasi, status_pelanggan, request_order_id) VALUES (?,?,?,?,'Installed',?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $_POST['detail_pelanggan_id'], $_POST['marketing_id'], $data['tanggal_survey'], $data['tanggal_instalasi'], $idOrder);
      } else {
        $sql = "INSERT INTO pipeline_marketings (detail_pelanggan_id, marketing_id, status_pelanggan, request_order_id) VALUES (?,?,'Installed',?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $_POST['detail_pelanggan_id'], $_POST['marketing_id'], $idOrder);
      }
      $stmt->execute();
      $idPipeline = $stmt->insert_id;

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
            $detail['request_order_id'] = $idOrder;
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

            $sql = "INSERT INTO detail_pipeline_marketings (barang_id, pipeline_marketing_id, kuantitas) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $detail['barang_id'], $idPipeline, $detail['kuantitas']);
            $stmt->execute();
          }
          if ($stmt->affected_rows > 0) {
            $response = ['success' => true, 'message' => "Berhasil menambahkan data. $error"];
          } else {
            $response = ['success' => false, 'message' => "Gagal menambahkan detail. $error"];
          }
        }
      } else {
        $response = ['success' => true, 'message' => "Berhasil menambahkan data. $error"];
      }
    } else {
      $response = ['success' => false, 'message' => "Gagal menambahkan data. $error"];
    }
    $conn->commit();
  } catch (Exception $e) {
    $conn->rollback();
    $response = ['success' => false, 'message' => $e->getMessage()];
  }
  echo json_encode($response);
}
