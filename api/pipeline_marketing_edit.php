<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $conn->begin_transaction();
    $id = $_POST['id'];
    unset($_POST['id']);

    $sql = "SELECT tanggal_dibuat FROM pipeline_marketings WHERE id = $id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $res = $stmt->get_result();
    $tanggal="";
    while ($row = $res->fetch_assoc()) {
      $tanggal = $row['tanggal_dibuat'];
    }
    if($tanggal!=date('Y-m-d')){
      throw new Exception('Pipeline Marketing hanya dapat diubah pada hari dibuatnya.');
    }

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
    $sql = "UPDATE pipeline_marketings SET ";
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
      $sql = "DELETE FROM detail_pipeline_marketings WHERE pipeline_marketing_id = $id";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      
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
        $response = ['success' => true, 'message' => "Berhasil mengubah data."];
      } else {
        $response = ['success' => false, 'message' => "Gagal mengubah detail."];
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
