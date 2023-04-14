<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $conn->begin_transaction();
    // Get data from the request
    if (isset($_POST['detail_pipeline_marketings'])) {
      $details = $_POST['detail_pipeline_marketings'];
      unset($_POST['detail_pipeline_marketings']);
    }

    // Get keys
    $keys = array_keys($_POST);

    // Get values
    $values = array_values($_POST);

    // Prepare SQL
    $placeholder = '';
    $params = '';
    $sql = "INSERT INTO pipeline_marketings (";
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
          $detail['pipeline_marketing_id'] = $id;
          // Get keys
          $keys = array_keys($detail);

          // Get values
          $values = array_values($detail);

          // Prepare SQL
          $placeholder = '';
          $params = '';
          $sql = "INSERT INTO detail_pipeline_marketings (";
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
