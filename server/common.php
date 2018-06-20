<?php
  // Include the vzaar library
  require('vzaar/lib/vzaar.php');

  // Authenticate with vzaar using environmet variables
  VzaarApi\Client::$client_id  = 'YOUR_CLIENT_ID';
  VzaarApi\Client::$auth_token = 'YOUR_AUTH_TOKEN';
?>
