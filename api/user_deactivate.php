<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $conn->begin_transaction();

    $sql = "UPDATE users SET aktif = 0 WHERE kode = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_POST['kode']);
    $stmt->execute();

    if (isset($_POST['pelanggans'])) {
      foreach ($_POST['pelanggans'] as $pelanggan) {
        $sql = "UPDATE pelanggans SET marketing_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $pelanggan['marketing_id'], $pelanggan['id']);
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
