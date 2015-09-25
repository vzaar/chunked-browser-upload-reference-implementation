<?php
  // common.php houses common setup and environment setup
  require("common.php");

  if (isset($_POST['guid'])) {
    $response = Vzaar::processVideo($_POST['guid'], $_POST['title'], $_POST['description'], 1);    
    echo(json_encode($response));
  }
?>
