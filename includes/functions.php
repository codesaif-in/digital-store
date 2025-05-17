<?php
function redirect($url) {
  header("Location: $url");
  exit();
}

function sanitize($data) {
  global $conn;
  return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}
?>