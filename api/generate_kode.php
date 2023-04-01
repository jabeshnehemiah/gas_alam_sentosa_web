<?php
header("Access-Control-Allow-Origin: *");
include 'connection.php';

function generateKode($table, $length, $conn)
{
  switch ($table) {
    case '':
      break;
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
