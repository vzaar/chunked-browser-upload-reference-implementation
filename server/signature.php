<?php
  // common.php houses common setup and environment setup
  require("common.php");

  // set the output type header, so that requesting clients are aware that the
  // response is JSON
  header("Content-Type: application/json");

  // get a signature from vzaar and output it as JSON
  // setting the second parameter to true indicates that we are requesting a
  // multipart signature
  echo(json_encode(Vzaar::getUploadSignature(null, true)));
?>
