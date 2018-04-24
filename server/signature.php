<?php
  // common.php houses common setup and environment setup
  require("common.php");

  // set the output type header, so that requesting clients are aware that the
  // response is JSON
  header("Content-Type: application/json");

  // get a signature from vzaar and output it as JSON
  $params['filename'] = $_GET['filename'];
  $params['filesize'] = $_GET['filesize'];

  if ($_GET["multipart"] === 'true') {
    $params['desired_part_size'] = $_GET['part_size'];
    $sig = VzaarApi\Signature::multipart($params);
  }
  else {
    $sig = VzaarApi\Signature::single($params);
  }

  echo json_encode([
    "guid"               => $sig->guid,
    "key"                => $sig->key,
    "parts"              => $sig->parts,
    "part_size"          => $sig->part_size,
    "part_size_in_bytes" => $sig->part_size_in_bytes,
    "upload_hostname"    => $sig->upload_hostname,
    "access_key_id"      => $sig->access_key_id,
    "acl"                => $sig->acl,
    "policy"             => $sig->policy,
    "signature"          => $sig->signature
  ]);
?>
