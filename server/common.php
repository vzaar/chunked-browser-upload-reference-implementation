<?php
  // Include the vzaar library
  require('vzaar/src/Vzaar.php');

  // Authenticate with vzaar using environmet variables
  Vzaar::$token = $_ENV['VZAAR_TOKEN'];
  Vzaar::$secret = $_ENV['VZAAR_SECRET'];
?>
