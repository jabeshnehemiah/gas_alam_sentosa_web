<?php
session_start();
session_destroy();
$response = ['success' => true, 'message' => 'Authentication successful'];
echo json_encode($response);
